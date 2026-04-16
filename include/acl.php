<?php

class SimpleAcl
{
    private array $allowRules = [];
    private array $denyRules = [];
    private array $roles = [];
    private array $resources = [];

    public function addRole(string $role): void
    {
        $this->roles[$role] = true;
    }

    public function addResource(string $resource): void
    {
        $this->resources[$resource] = true;
    }

    public function allow(?string $role, ?string $resource = null, ?string $privilege = null): void
    {
        $this->allowRules[] = [$role, $resource, $privilege];
    }

    public function deny(?string $role, ?string $resource = null, ?string $privilege = null): void
    {
        $this->denyRules[] = [$role, $resource, $privilege];
    }

    public function isAllowed(?string $role, ?string $resource, ?string $privilege = null): bool
    {
        $role = $role ?: 'guest';

        foreach ($this->denyRules as $rule) {
            if ($this->matchesRule($rule, $role, $resource, $privilege)) {
                return false;
            }
        }

        foreach ($this->allowRules as $rule) {
            if ($this->matchesRule($rule, $role, $resource, $privilege)) {
                return true;
            }
        }

        return false;
    }

    private function matchesRule(array $rule, string $role, ?string $resource, ?string $privilege): bool
    {
        [$ruleRole, $ruleResource, $rulePrivilege] = $rule;

        if ($ruleRole !== null && $ruleRole !== $role) {
            return false;
        }

        if ($ruleResource !== null && $ruleResource !== $resource) {
            return false;
        }

        if ($rulePrivilege !== null && $rulePrivilege !== $privilege) {
            return false;
        }

        return true;
    }
}

$acl = new SimpleAcl();

//create the user roles
$acl->addRole('administrator');
$acl->addRole('domain_administrator');
$acl->addRole('user');
$acl->addRole('operator');
$acl->addRole('viewer');
$acl->addRole('customer');
$acl->addRole('biller');

//create the resources
$acl->addResource('admin');
$acl->addResource('domain_admin');
$acl->addResource('api');
$acl->addResource('auth');
$acl->addResource('billers');
$acl->addResource('cron');
$acl->addResource('custom_fields');
$acl->addResource('customers');
$acl->addResource('documentation');
$acl->addResource('export');
$acl->addResource('extensions');
$acl->addResource('index');
$acl->addResource('install');
$acl->addResource('inventory');
$acl->addResource('invoices');
$acl->addResource('options');
$acl->addResource('payment_types');
$acl->addResource('payments');
$acl->addResource('preferences');
$acl->addResource('product_attribute');
$acl->addResource('product_value');
$acl->addResource('products');
$acl->addResource('reports');
$acl->addResource('statement');
$acl->addResource('system_defaults');
$acl->addResource('tax_rates');
$acl->addResource('user');
$acl->addResource('expense');
$acl->addResource('expense_account');

// allow rules
$acl->allow(null, 'auth');
$acl->allow(null, 'api');
$acl->allow(null, 'payments', 'ach');
$acl->allow(null, 'invoices');
$acl->allow('customer', 'customers', 'view');
$acl->allow('domain_administrator');
$acl->allow('administrator');
$acl->allow('user');
$acl->allow('operator');
$acl->allow('operator', 'invoices', 'manage');
$acl->allow('operator', 'billers', 'manage');
$acl->allow('operator', 'billers', 'view');
$acl->allow('operator', 'customers', 'manage');
$acl->allow('operator', 'customers', 'view');

// deny rules — admin panel is administrator-only; domain_admin for domain_administrator+administrator
$acl->deny('user', 'domain_admin');
$acl->deny('operator', 'domain_admin');
$acl->deny('viewer', 'domain_admin');
$acl->deny('customer', 'domain_admin');
$acl->deny('biller', 'domain_admin');

$acl->deny('domain_administrator', 'admin');
$acl->deny('user', 'admin');
$acl->deny('operator', 'admin');
$acl->deny('viewer', 'admin');
$acl->deny('customer', 'admin');
$acl->deny('biller', 'admin');

$acl->deny('user', 'options');
$acl->deny('user', 'system_defaults');
$acl->deny('user', 'custom_fields');
$acl->deny('user', 'user');
$acl->deny('user', 'tax_rates');
$acl->deny('user', 'preferences');
$acl->deny('user', 'payment_types');
$acl->deny('operator', 'options');
$acl->deny('operator', 'system_defaults');
$acl->deny('operator', 'custom_fields');
$acl->deny('operator', 'user');
$acl->deny('operator', 'tax_rates');
$acl->deny('operator', 'preferences');
$acl->deny('operator', 'payment_types');
$acl->deny('operator', 'cron');
$acl->deny('operator', 'invoices', 'view');
$acl->deny('operator', 'billers', 'add');
$acl->deny('operator', 'billers', 'edit');
$acl->deny('operator', 'customers', 'add');
$acl->deny('operator', 'customers', 'edit');
