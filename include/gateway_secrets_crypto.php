<?php

/**
 * Libsodium secretbox encryption for sensitive payment-gateway fields on si_biller.
 * Values are stored as si1:<base64(nonce || ciphertext)> when a key is configured.
 * Without SI_GATEWAY_SECRETS_KEY / encryption.gateway_secrets.key, values remain plaintext.
 */

declare(strict_types=1);

/**
 * Biller columns that must never be logged or displayed in full; encrypted at rest when a key is set.
 *
 * @return list<string>
 */
function si_biller_secret_field_names(): array
{
	return [
		'stripe_secret_key',
		'stripe_webhook_secret',
		'paypal_client_secret',
		'mollie_api_key',
		'authorizenet_login_id',
		'authorizenet_transaction_key',
		'authorizenet_signature_key',
		'eway_api_key',
		'eway_api_password',
		'coinbase_api_key',
		'coinbase_webhook_secret',
		'adyen_api_key',
		'adyen_hmac_key',
	];
}

function si_gateway_secret_storage_prefix(): string
{
	return 'si1:';
}

/**
 * Raw 32-byte key for sodium_crypto_secretbox, or null if encryption is disabled / misconfigured.
 */
function si_gateway_secrets_get_raw_key(): ?string
{
	global $config;

	if (!extension_loaded('sodium')) {
		return null;
	}

	$candidate = getenv('SI_GATEWAY_SECRETS_KEY');
	if ($candidate === false || $candidate === '') {
		$enc = $config->encryption ?? null;
		$gws = ($enc !== null && isset($enc->gateway_secrets)) ? $enc->gateway_secrets : null;
		$candidate = ($gws !== null && isset($gws->key)) ? (string) $gws->key : '';
	}
	$candidate = trim((string) $candidate);
	if ($candidate === '') {
		return null;
	}

	if (strlen($candidate) === 64 && ctype_xdigit($candidate)) {
		return sodium_hex2bin($candidate);
	}

	$decoded = base64_decode($candidate, true);
	if ($decoded !== false && strlen($decoded) === SODIUM_CRYPTO_SECRETBOX_KEYBYTES) {
		return $decoded;
	}

	if (strlen($candidate) === SODIUM_CRYPTO_SECRETBOX_KEYBYTES) {
		return $candidate;
	}

	error_log('SI_GATEWAY_SECRETS_KEY / encryption.gateway_secrets.key: invalid format (use 64 hex chars, base64 of 32 bytes, or raw 32 bytes)');
	return null;
}

function si_gateway_secret_encrypt(string $plaintext, ?string $rawKey): string
{
	if ($plaintext === '') {
		return '';
	}
	if ($rawKey === null || !extension_loaded('sodium')) {
		return $plaintext;
	}

	$nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
	$cipher = sodium_crypto_secretbox($plaintext, $nonce, $rawKey);

	return si_gateway_secret_storage_prefix() . sodium_bin2base64($nonce . $cipher, SODIUM_BASE64_VARIANT_ORIGINAL);
}

function si_gateway_secret_decrypt(string $stored, ?string $rawKey): string
{
	if ($stored === '') {
		return '';
	}

	$prefix = si_gateway_secret_storage_prefix();
	if (!str_starts_with($stored, $prefix)) {
		return $stored;
	}

	if ($rawKey === null || !extension_loaded('sodium')) {
		error_log('Gateway secret is encrypted (si1:) but no valid libsodium key is configured');
		return '';
	}

	$decoded = sodium_base642bin(substr($stored, strlen($prefix)), SODIUM_BASE64_VARIANT_ORIGINAL);
	if ($decoded === false) {
		return '';
	}

	$nonceLen = SODIUM_CRYPTO_SECRETBOX_NONCEBYTES;
	if (strlen($decoded) < $nonceLen + SODIUM_CRYPTO_SECRETBOX_MACBYTES) {
		error_log('Gateway secret ciphertext too short');
		return '';
	}

	$nonce = substr($decoded, 0, $nonceLen);
	$cipher = substr($decoded, $nonceLen);
	$plain = sodium_crypto_secretbox_open($cipher, $nonce, $rawKey);
	if ($plain === false) {
		error_log('Gateway secret decrypt failed (wrong key or corrupted data)');
		return '';
	}

	return $plain;
}

/**
 * @param array<string, mixed> $row
 * @return array<string, mixed>
 */
function si_biller_row_decrypt_gateway_secrets(array $row): array
{
	$key = si_gateway_secrets_get_raw_key();
	foreach (si_biller_secret_field_names() as $field) {
		if (!array_key_exists($field, $row)) {
			continue;
		}
		$row[$field] = si_gateway_secret_decrypt((string) ($row[$field] ?? ''), $key);
	}

	return $row;
}
