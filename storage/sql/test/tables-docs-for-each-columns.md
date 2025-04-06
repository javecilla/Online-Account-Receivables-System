# Database Tables Documentation

## roles
| Column | Type | Description |
|--------|------|-------------|
| role_id | INT | Primary identifier for each role |
| role_name | VARCHAR(50) | Unique name of the role (e.g., Administrator, Accountant, Member) |
| created_at | TIMESTAMP | When the role was created |
| updated_at | TIMESTAMP | Last modification timestamp |

## users
| Column | Type | Description |
|--------|------|-------------|
| user_id | INT | Primary identifier for each user |
| role_id | INT | References roles table to determine user permissions |
| first_name | VARCHAR(50) | User's first name for identification |
| last_name | VARCHAR(50) | User's last name for identification |
| email | VARCHAR(100) | Unique email for login and communications |
| password | VARCHAR(255) | Hashed password for authentication |
| member_id | VARCHAR(20) | Unique member identifier for organization tracking |
| status | ENUM | Account status: active/inactive/suspended |
| created_at | TIMESTAMP | Account creation timestamp |
| updated_at | TIMESTAMP | Last account modification timestamp |

## invoices
| Column | Type | Description |
|--------|------|-------------|
| invoice_id | INT | Primary identifier for each invoice |
| user_id | INT | References user who owns the invoice |
| invoice_number | VARCHAR(20) | Unique invoice identifier for tracking |
| amount | DECIMAL(10,2) | Total amount due on invoice |
| due_date | DATE | When payment is expected |
| description | TEXT | Details about the invoice |
| status | ENUM | Payment status: pending/paid/overdue/cancelled |
| is_recurring | BOOLEAN | Whether invoice repeats periodically |
| recurring_period | ENUM | Frequency: monthly/quarterly/annually |
| created_at | TIMESTAMP | When invoice was generated |
| updated_at | TIMESTAMP | Last invoice modification timestamp |

## payments
| Column | Type | Description |
|--------|------|-------------|
| payment_id | INT | Primary identifier for each payment |
| invoice_id | INT | References the invoice being paid |
| user_id | INT | References user making the payment |
| amount | DECIMAL(10,2) | Amount paid |
| payment_date | DATE | When payment was made |
| payment_method | ENUM | Method: cash/bank_transfer/check |
| reference_number | VARCHAR(50) | Transaction reference for tracking |
| proof_of_payment | VARCHAR(255) | Path to uploaded payment proof |
| status | ENUM | Verification status: pending/verified/rejected |
| notes | TEXT | Additional payment information |
| created_at | TIMESTAMP | When payment was recorded |
| updated_at | TIMESTAMP | Last payment record modification |

## notifications
| Column | Type | Description |
|--------|------|-------------|
| notification_id | INT | Primary identifier for each notification |
| user_id | INT | References user receiving notification |
| title | VARCHAR(100) | Brief notification subject |
| message | TEXT | Detailed notification content |
| type | ENUM | Category: payment_reminder/overdue_notice/system_alert/payment_confirmation |
| is_read | BOOLEAN | Whether user has viewed notification |
| created_at | TIMESTAMP | When notification was generated |

## email_logs
| Column | Type | Description |
|--------|------|-------------|
| log_id | INT | Primary identifier for each email log |
| user_id | INT | References recipient user |
| subject | VARCHAR(255) | Email subject line |
| message | TEXT | Email content |
| status | ENUM | Delivery status: pending/sent/failed |
| error_message | TEXT | Details if email failed |
| sent_at | TIMESTAMP | When email was sent |
| created_at | TIMESTAMP | When log entry was created |
