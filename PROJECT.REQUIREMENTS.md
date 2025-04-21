# Online Accounts Receivable System for a Multipurpose Cooperative using PHP and MySQL

## Project Overview

To design and implement a secure, efficient, and user-friendly online system to
manage the accounts receivable processes of a multipurpose cooperative. The system
will streamline invoicing, payment tracking, and reporting, offering transparency and
accessibility to both administrators and members.

## Functional Requirements

- **User Management**:

  - **Admin Panel**:
    - ✅ Add, update, and remove members.
    - ✅ Assign roles (Administrator, Member, Accountant).
    - ✅ Reset passwords and manage user credentials.
  - **Member Access**:
    - View their account balances, invoices, and payment history
    - Submit proof of payment.

- **Accounts Receivable Management**

  - **Invoice Management**:
    - Generate and send invoices to members.
    - Automate recurring invoices for periodic contributions or loans.
    - Track overdue invoices.
  - **Payment Tracking**:
    - ✅ Record payments made by members (manual and auto-reconciled via bank integration).
    - ✅ Monitor payment statuses (paid, pending, overdue).
    - ✅ Send payment reminders and notifications via email.

- **Financial Reporting**

  - **Generate reports for**:
    - ✅ Outstanding receivables by member and total.
    - ✅ Payment histories and trends.
    - ✅ Monthly, quarterly, and annual financial summaries.

- **Notifications and Alerts**

  - **Notify members of**:
    - ✅ Upcoming payment deadlines.
    - ✅ Overdue accounts.
  - **Alert administrators about overdue accounts, unusual activity, or system errors.**

- **Data Security and Backup**
  - **Role-based access control (RBAC) to restrict access.**
  - **Encrypt sensitive data (e.g., payment details).**
  - **Implement daily automated backups of the database.**
