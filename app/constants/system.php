<?php

declare(strict_types=1);

const DEFAULT_ADMIN_ID = 1; //admin id

const ADMINISTRATOR = 'Administrator';
const ACCOUNTANT = 'Accountant';
const MEMBER = 'Member';
const ACCOUNT_ROLES = [ADMINISTRATOR, ACCOUNTANT, MEMBER];

const ACTIVE = 'active';
const INACTIVE = 'inactive';
const ACCOUNT_STATUS = [ACTIVE, INACTIVE];

const DEPOSIT = 'deposit';
const INTEREST = 'interest';
const WITHDRAWAL = 'withdrawal';
const FEE = 'fee';
const TRANSACTION_TYPES = [DEPOSIT, INTEREST, WITHDRAWAL, FEE];

const MINIMUM_PENALTY = 50.00;

const SUSPENDED = 'suspended';
const CLOSED = 'closed';
const MEMBERSHIP_STATUS = [ACTIVE, INACTIVE, SUSPENDED, CLOSED];


const SAVINGS_ACCOUNT = 'Savings Account';
const TIME_DEPOSIT = 'Time Deposit';
const FIXED_DEPOSIT = 'Fixed Deposit';
const SPECIAL_SAVINGS = 'Special Savings';
const YOUTH_SAVINGS = 'Youth Savings';
const LOAN = 'Loan';
//account types
const MEMBERSHIP_TYPES = [
    SAVINGS_ACCOUNT,
    TIME_DEPOSIT,
    FIXED_DEPOSIT,
    SPECIAL_SAVINGS,
    YOUTH_SAVINGS,
    LOAN
];

const AMORTIZATION_ACTIVE = 'active';
const AMORTIZATION_COMPLETED = 'completed';
const AMORTIZATION_DEFAULTED = 'defaulted';
const AMORTIZATION_STATUS = [AMORTIZATION_ACTIVE, AMORTIZATION_COMPLETED, AMORTIZATION_DEFAULTED];

const AMORTIZATION_PENDING = 'pending';
const AMORTIZATION_APPROVED = 'approved';
const AMORTIZATION_REJECTED = 'rejected';
const AMORTIZATION_APPROVAL_STATUS = [AMORTIZATION_PENDING, AMORTIZATION_APPROVED, AMORTIZATION_REJECTED];
