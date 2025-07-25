<?php

declare(strict_types=1);

const DEFAULT_ADMIN_ID = 1; //admin id

const SUPER_ADMIN = 'Super Admin';
const ADMINISTRATOR = 'Administrator';
const ACCOUNTANT = 'Accountant';
const MEMBER = 'Member';
const ACCOUNT_ROLES = [SUPER_ADMIN, ADMINISTRATOR, ACCOUNTANT, MEMBER];
const EMPLOYEE_ROLES = [SUPER_ADMIN, ADMINISTRATOR, ACCOUNTANT];

const ACTIVE = 'active';
const INACTIVE = 'inactive';
const ACCOUNT_STATUS = [ACTIVE, INACTIVE];

const VERIFIED = 'verified';
const UNVERIFIED = 'unverified';
const ACCOUNT_VERIFICATION = [VERIFIED, UNVERIFIED];

const DEPOSIT = 'deposit';
const INTEREST = 'interest';
const WITHDRAWAL = 'withdrawal';
const FEE = 'fee';
const CREDIT = 'credit';
const CREDIT_USED = 'credit_used';
const DEBIT = 'debit'; // Note: Not used in TRANSACTION_TYPES, but included for completeness
const LOAN_PAYMENT = 'loan_payment';
const TRANSACTION_TYPES = [DEPOSIT, INTEREST, WITHDRAWAL, FEE, CREDIT, CREDIT_USED, LOAN_PAYMENT];

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

const AMORTIZATION_PENDING = 'pending';
const AMORTIZATION_APPROVED = 'approved';
const AMORTIZATION_REJECTED = 'rejected';
const AMORTIZATION_APPROVAL_STATUS = [AMORTIZATION_PENDING, AMORTIZATION_APPROVED, AMORTIZATION_REJECTED];

const AMORTIZATION_ACTIVE = 'active';
const AMORTIZATION_COMPLETED = 'completed';
const AMORTIZATION_DEFAULTED = 'defaulted';
// const AMORTIZATION_STATUS = [AMORTIZATION_ACTIVE, AMORTIZATION_COMPLETED, AMORTIZATION_DEFAULTED];
const AMORTIZATION_PAID = 'paid';
const AMORTIZATION_OVERDUE = 'overdue';
const AMORTIZATION_STATUS = [AMORTIZATION_PAID, AMORTIZATION_PENDING, AMORTIZATION_OVERDUE, AMORTIZATION_DEFAULTED];

const EDUCATIONAL_LOAN = 'Educational Loan';
const CALAMITY_LOAN = 'Calamity Loan';
const BUSINESS_LOAN = 'Business Loan';
const PERSONAL_LOAN = 'Personal Loan';
const AGRICULTURAL_LOAN = 'Agricultural Loan';
const LOAN_TYPE_NAMES = [EDUCATIONAL_LOAN, CALAMITY_LOAN, BUSINESS_LOAN, PERSONAL_LOAN, AGRICULTURAL_LOAN];

const PENDING = 'pending';
const APPROVED = 'approved';
const REJECTED = 'rejected';
const MEMBERSHIP_APPROVAL_STATUS = [PENDING, APPROVED, REJECTED];
