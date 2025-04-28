1. Regular Invoice Generation
   Scenario: Monthly Membership Fee

- Admin creates invoice for member
- System sets: invoice_number (auto), amount ($50), due_date (30 days)
- Status starts as 'pending'
- Description: "Monthly Membership Fee - January 2024"

2. Recurring Invoice Setup
   Scenario: Quarterly Maintenance Fee

- Admin sets up recurring invoice
- System flags is_recurring = TRUE
- Sets recurring_period = 'quarterly'
- Amount: $150 per quarter
- # System auto-generates every 3 months

### Invoice Creation Fields Explained

1. **Due Date Field**

- The due date represents when the payment is expected from the member
- For regular (non-recurring) invoices:
  - Admin can select a specific due date
  - System can suggest a default (e.g., +30 days from creation)
- For recurring invoices:
  - Initial due date is set for the first invoice
  - Subsequent due dates are automatically calculated based on the recurring_period

2. **is_recurring Field (Boolean)**

- Purpose: Tells the system whether this is a one-time invoice or a repeating one
- When TRUE:
  - System will automatically generate new invoices based on recurring_period
  - Each new invoice inherits the original invoice's properties (amount, description)
  - Maintains the same member and invoice type
- When FALSE:
  - Creates a single invoice only
  - No automatic generation of future invoices

3. **recurring_period Field**

```sql
recurring_period ENUM('monthly', 'quarterly', 'annually')
```

This field determines the frequency of automatic invoice generation:

- Monthly: New invoice every month
- Quarterly: New invoice every 3 months
- Annually: New invoice every year

### Example Scenario: Quarterly Maintenance Fee

Let's say an admin creates a recurring maintenance fee invoice:

```
Initial Invoice Setup:
- Member: John Doe
- Invoice Number: INV-2024-001 (auto-generated)
- Amount: ₱150
- Description: "Quarterly Maintenance Fee"
- Due Date: March 15, 2024 (first payment)
- is_recurring: TRUE
- recurring_period: 'quarterly'
```

**System Automation:**

1. First invoice is created immediately with due date March 15, 2024
2. System automatically generates next invoices:
   - INV-2024-002 (Due: June 15, 2024)
   - INV-2024-003 (Due: September 15, 2024)
   - INV-2024-004 (Due: December 15, 2024)

Each automatically generated invoice:

- Gets a new unique invoice number
- Inherits the original amount (₱150)
- Has due date calculated based on the recurring period
- Starts with 'pending' status
- Maintains the same description

This setup allows the admin to:

1. Create one invoice setup that handles recurring charges
2. Automatically track multiple future payments
3. Maintain consistent billing cycles
4. Reduce manual invoice creation work

The system handles:

- Automatic generation of new invoices
- Due date calculations
- Status tracking for each invoice separately
- Notification generation for upcoming due dates

This automation helps meet the project requirement of "Automate recurring invoices for periodic contributions or loans" while maintaining organized tracking of all payments.

---

recurring invoices work in relation to late payments and invoice generation:

1. **Recurring Invoices vs Late Payments**

- Recurring invoices are NOT specifically for late payments
- They are for regular, scheduled charges like:
  - Monthly membership dues
  - Quarterly maintenance fees
  - Annual registration fees
- Late payments are handled separately through the invoice `status` field changing to 'overdue'

2. **Late Payment Handling**
   When a member fails to pay an invoice on time:

- The system automatically marks the invoice as 'overdue'
- Admin can then:
  - Send payment reminders
  - Apply late payment penalties through a new, separate invoice
  - Track the overdue amount

3. **Manual Invoice Generation**
   Yes, admin should have buttons/actions to:

- Generate new invoices manually for any purpose
- Create penalty invoices for late payments
- Generate recurring invoice series

Here's a practical example:

**Scenario 1: Regular Recurring Invoice**

```
Member A: Monthly Maintenance Fee
- Initial Invoice (INV-2024-001)
  - Amount: ₱150
  - Due: March 1, 2024
  - Status: pending

System automatically generates:
- INV-2024-002 (Due: April 1, 2024)
- INV-2024-003 (Due: May 1, 2024)
```

**Scenario 2: Late Payment Handling**

```
If INV-2024-001 is not paid by March 1:
1. Status changes to 'overdue'
2. Admin can generate a penalty invoice:
   - New Invoice (INV-2024-PEN-001)
   - Amount: Late fee (based on member_types.penalty_rate)
   - Due: Immediate
   - Description: "Late Payment Penalty for INV-2024-001"
```

The recurring system continues generating new invoices on schedule, regardless of payment status of previous invoices. This ensures:

1. Regular fees continue to be tracked
2. Late payments and penalties are handled separately
3. Clear audit trail of all charges and penalties

This approach aligns with your database structure where:

- <mcfile name="db-design.sql" path="c:\xampp\htdocs\bulsu-school\account-receivable-system-project\storage\sql\updated\db-design.sql"></mcfile> includes both `member_types.penalty_rate` for calculating late fees and `member_invoices` for tracking all types of charges.

=================================================

3. Overdue Invoice Handling
   Scenario: Late Payment Processing

- System automatically flags invoices as 'overdue' past due_date
- Admin can view overdue invoices report
- System sends automatic reminders
- Admin can apply late payment penalties via new invoice

4. Payment Processing
   Scenario: Member Payment Recording

- Admin receives payment confirmation
- Updates invoice status to 'paid'
- System automatically:
  - Updates member's current_balance
  - Records transaction timestamp
  - Sends payment confirmation

=================================================================
MGA PURPOSE NG ACTIONS for RECURRING INVOICES:

- View Recurring Invoice

  - Shows detailed information about the recurring invoice setup
  - Displays history of generated invoices from this recurring schedule
  - Presents next generation date and payment statistics

- Edit Recurring Invoice

  - Allows modification of:
    - Amount
    - Description
    - Recurring period (monthly, quarterly, annually)
    - Next due date
  - Changes only affect future generated invoices

- Notify Members

  - Sends reminders about upcoming invoice generation
  - Alerts members about changes in recurring schedule
  - Option to preview notification before sending

- Generate Invoices

  - Manually triggers invoice generation for the next period
  - Useful for testing or handling special cases
  - Creates regular invoices based on recurring template

- Cancel Recurring Invoice

  - Stops future invoice generation
  - Maintains history of previously generated invoices
  - Requires confirmation to prevent accidental cancellation

- (Update Status) Pause/Resume Functionality

  - Temporarily suspend invoice generation without canceling
  - Useful for handling member payment holidays or special circumstances
  - Should be added as a new action button with status indicator

- History View Enhancement
  - Add a "View History" button to show:
    - List of all generated invoices
    - Payment status for each invoice
    - Total amount collected
    - Payment trends
