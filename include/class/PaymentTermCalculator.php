<?php

/**
 * Computes invoice due dates from payment term definitions (see si_payment_terms).
 */
class PaymentTermCalculator
{
    public const KIND_NET_DAYS = 'NET_DAYS';
    public const KIND_EOM = 'EOM';
    public const KIND_EOM_PLUS_DAYS = 'EOM_PLUS_DAYS';
    public const KIND_MFI_DAY = 'MFI_DAY';

    /**
     * @param string $invoiceYmd Invoice date Y-m-d (date portion only)
     * @param array{calc_kind?:string,param_int?:int|null} $termRow Row from si_payment_terms
     * @return string Due date Y-m-d
     */
    public static function dueDateFromTerm(string $invoiceYmd, array $termRow): string
    {
        $kind = $termRow['calc_kind'] ?? '';
        $param = isset($termRow['param_int']) ? (int) $termRow['param_int'] : null;

        $inv = date_create_immutable($invoiceYmd);
        if ($inv === false) {
            return $invoiceYmd;
        }

        switch ($kind) {
            case self::KIND_NET_DAYS:
                $n = $param ?? 0;
                $d = $inv->modify('+' . $n . ' days');
                return $d->format('Y-m-d');

            case self::KIND_EOM:
                $eom = $inv->modify('last day of this month');
                return $eom->format('Y-m-d');

            case self::KIND_EOM_PLUS_DAYS:
                $n = $param ?? 0;
                $eom = $inv->modify('last day of this month');
                $d = $eom->modify('+' . $n . ' days');
                return $d->format('Y-m-d');

            case self::KIND_MFI_DAY:
                $day = max(1, min(31, $param ?? 1));
                $firstNext = $inv->modify('first day of next month');
                $dim = (int) $firstNext->format('t');
                $use = min($day, $dim);
                $y = (int) $firstNext->format('Y');
                $m = (int) $firstNext->format('m');
                $d = $firstNext->setDate($y, $m, $use);
                return $d->format('Y-m-d');

            default:
                return $invoiceYmd;
        }
    }
}
