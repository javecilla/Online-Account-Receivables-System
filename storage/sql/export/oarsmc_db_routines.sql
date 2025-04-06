-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: oarsmc_db
-- ------------------------------------------------------
-- Server version	8.0.40

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Temporary view structure for view `vw_dashboard_summary`
--

DROP TABLE IF EXISTS `vw_dashboard_summary`;
/*!50001 DROP VIEW IF EXISTS `vw_dashboard_summary`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_dashboard_summary` AS SELECT 
 1 AS `total_active_members`,
 1 AS `total_inactive_members`,
 1 AS `member_activity_rate`,
 1 AS `total_defaulted_members`,
 1 AS `average_default_rate`,
 1 AS `avg_accounts_below_minimum_pct`,
 1 AS `active_loans_count`,
 1 AS `total_outstanding_loans`,
 1 AS `loan_completion_rate`,
 1 AS `total_active_balances`,
 1 AS `current_month_collection_efficiency`,
 1 AS `new_members_this_month`,
 1 AS `member_growth_from_last_month`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_member_growth_analysis`
--

DROP TABLE IF EXISTS `vw_member_growth_analysis`;
/*!50001 DROP VIEW IF EXISTS `vw_member_growth_analysis`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_member_growth_analysis` AS SELECT 
 1 AS `month_year`,
 1 AS `new_members`,
 1 AS `active_members`,
 1 AS `inactive_members`,
 1 AS `suspended_members`,
 1 AS `closed_members`,
 1 AS `avg_opening_balance`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_monthly_receivables_trend`
--

DROP TABLE IF EXISTS `vw_monthly_receivables_trend`;
/*!50001 DROP VIEW IF EXISTS `vw_monthly_receivables_trend`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_monthly_receivables_trend` AS SELECT 
 1 AS `month_date`,
 1 AS `total_principal`,
 1 AS `total_remaining`,
 1 AS `total_collected`,
 1 AS `total_loans`,
 1 AS `overdue_loans`,
 1 AS `collection_rate`,
 1 AS `overdue_rate`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_account_details`
--

DROP TABLE IF EXISTS `vw_account_details`;
/*!50001 DROP VIEW IF EXISTS `vw_account_details`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_account_details` AS SELECT 
 1 AS `account_id`,
 1 AS `account_uid`,
 1 AS `email`,
 1 AS `username`,
 1 AS `account_status`,
 1 AS `email_verified_at`,
 1 AS `first_login_at`,
 1 AS `role_name`,
 1 AS `created_at`,
 1 AS `updated_at`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_loan_performance_analytics`
--

DROP TABLE IF EXISTS `vw_loan_performance_analytics`;
/*!50001 DROP VIEW IF EXISTS `vw_loan_performance_analytics`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_loan_performance_analytics` AS SELECT 
 1 AS `loan_type`,
 1 AS `total_loans`,
 1 AS `total_principal`,
 1 AS `total_remaining`,
 1 AS `avg_monthly_payment`,
 1 AS `completed_loans`,
 1 AS `defaulted_loans`,
 1 AS `active_loans`,
 1 AS `default_rate`,
 1 AS `avg_loan_duration_days`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_employee_details`
--

DROP TABLE IF EXISTS `vw_employee_details`;
/*!50001 DROP VIEW IF EXISTS `vw_employee_details`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_employee_details` AS SELECT 
 1 AS `employee_id`,
 1 AS `first_name`,
 1 AS `middle_name`,
 1 AS `last_name`,
 1 AS `full_name`,
 1 AS `contact_number`,
 1 AS `salary`,
 1 AS `rata`,
 1 AS `account_uid`,
 1 AS `email`,
 1 AS `account_status`,
 1 AS `role_name`,
 1 AS `created_at`,
 1 AS `updated_at`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_amortization_payment_summary`
--

DROP TABLE IF EXISTS `vw_amortization_payment_summary`;
/*!50001 DROP VIEW IF EXISTS `vw_amortization_payment_summary`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_amortization_payment_summary` AS SELECT 
 1 AS `member_id`,
 1 AS `member_uid`,
 1 AS `full_name`,
 1 AS `amortization_id`,
 1 AS `type_name`,
 1 AS `principal_amount`,
 1 AS `monthly_amount`,
 1 AS `remaining_balance`,
 1 AS `total_payments`,
 1 AS `total_amount_paid`,
 1 AS `total_payable`,
 1 AS `first_payment_date`,
 1 AS `last_payment_date`,
 1 AS `start_date`,
 1 AS `end_date`,
 1 AS `status`,
 1 AS `payment_status`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_monthly_balance_trends`
--

DROP TABLE IF EXISTS `vw_monthly_balance_trends`;
/*!50001 DROP VIEW IF EXISTS `vw_monthly_balance_trends`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_monthly_balance_trends` AS SELECT 
 1 AS `month_year`,
 1 AS `account_type`,
 1 AS `total_members`,
 1 AS `total_transactions`,
 1 AS `total_credits`,
 1 AS `total_debits`,
 1 AS `average_balance`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_monthly_overdue_metrics`
--

DROP TABLE IF EXISTS `vw_monthly_overdue_metrics`;
/*!50001 DROP VIEW IF EXISTS `vw_monthly_overdue_metrics`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_monthly_overdue_metrics` AS SELECT 
 1 AS `month_date`,
 1 AS `total_loans`,
 1 AS `avg_days_overdue`,
 1 AS `avg_payment_delay`,
 1 AS `overdue_count`,
 1 AS `delayed_payment_count`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_dashboard_metrics`
--

DROP TABLE IF EXISTS `vw_dashboard_metrics`;
/*!50001 DROP VIEW IF EXISTS `vw_dashboard_metrics`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_dashboard_metrics` AS SELECT 
 1 AS `total_active_balances`,
 1 AS `total_active_members`,
 1 AS `total_receivables`,
 1 AS `total_borrowers`,
 1 AS `overdue_receivables`,
 1 AS `overdue_accounts`,
 1 AS `overdue_percentage`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_amortization_details`
--

DROP TABLE IF EXISTS `vw_amortization_details`;
/*!50001 DROP VIEW IF EXISTS `vw_amortization_details`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_amortization_details` AS SELECT 
 1 AS `amortization_id`,
 1 AS `member_id`,
 1 AS `type_id`,
 1 AS `principal_amount`,
 1 AS `monthly_amount`,
 1 AS `remaining_balance`,
 1 AS `start_date`,
 1 AS `end_date`,
 1 AS `status`,
 1 AS `created_at`,
 1 AS `type_name`,
 1 AS `description`,
 1 AS `interest_rate`,
 1 AS `total_paid`,
 1 AS `balance_due`,
 1 AS `current_balance`,
 1 AS `full_name`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_membership_status_summary`
--

DROP TABLE IF EXISTS `vw_membership_status_summary`;
/*!50001 DROP VIEW IF EXISTS `vw_membership_status_summary`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_membership_status_summary` AS SELECT 
 1 AS `type_name`,
 1 AS `membership_status`,
 1 AS `total_members`,
 1 AS `total_balance`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_daily_transaction_summary`
--

DROP TABLE IF EXISTS `vw_daily_transaction_summary`;
/*!50001 DROP VIEW IF EXISTS `vw_daily_transaction_summary`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_daily_transaction_summary` AS SELECT 
 1 AS `transaction_date`,
 1 AS `transaction_type`,
 1 AS `transaction_count`,
 1 AS `total_amount`,
 1 AS `min_amount`,
 1 AS `max_amount`,
 1 AS `avg_amount`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_member_details`
--

DROP TABLE IF EXISTS `vw_member_details`;
/*!50001 DROP VIEW IF EXISTS `vw_member_details`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_member_details` AS SELECT 
 1 AS `member_id`,
 1 AS `member_uid`,
 1 AS `first_name`,
 1 AS `middle_name`,
 1 AS `last_name`,
 1 AS `full_name`,
 1 AS `contact_number`,
 1 AS `membership_status`,
 1 AS `current_balance`,
 1 AS `opened_date`,
 1 AS `closed_date`,
 1 AS `house_address`,
 1 AS `barangay`,
 1 AS `municipality`,
 1 AS `province`,
 1 AS `region`,
 1 AS `full_address`,
 1 AS `membership_type`,
 1 AS `minimum_balance`,
 1 AS `interest_rate`,
 1 AS `account_uid`,
 1 AS `email`,
 1 AS `account_status`,
 1 AS `registered_at`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_risk_assessment_dashboard`
--

DROP TABLE IF EXISTS `vw_risk_assessment_dashboard`;
/*!50001 DROP VIEW IF EXISTS `vw_risk_assessment_dashboard`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_risk_assessment_dashboard` AS SELECT 
 1 AS `account_type`,
 1 AS `total_accounts`,
 1 AS `accounts_below_minimum`,
 1 AS `percent_below_minimum`,
 1 AS `members_with_defaults`,
 1 AS `default_rate`,
 1 AS `avg_balance`,
 1 AS `min_balance`,
 1 AS `max_balance`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_member_locations`
--

DROP TABLE IF EXISTS `vw_member_locations`;
/*!50001 DROP VIEW IF EXISTS `vw_member_locations`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_member_locations` AS SELECT 
 1 AS `province`,
 1 AS `municipality`,
 1 AS `barangay`,
 1 AS `member_count`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_active_accounts_summary`
--

DROP TABLE IF EXISTS `vw_active_accounts_summary`;
/*!50001 DROP VIEW IF EXISTS `vw_active_accounts_summary`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_active_accounts_summary` AS SELECT 
 1 AS `type_name`,
 1 AS `total_members`,
 1 AS `total_balance`,
 1 AS `min_balance`,
 1 AS `max_balance`,
 1 AS `avg_balance`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_payment_collection_efficiency`
--

DROP TABLE IF EXISTS `vw_payment_collection_efficiency`;
/*!50001 DROP VIEW IF EXISTS `vw_payment_collection_efficiency`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_payment_collection_efficiency` AS SELECT 
 1 AS `loan_type`,
 1 AS `year`,
 1 AS `month`,
 1 AS `active_loans`,
 1 AS `payments_made`,
 1 AS `collected_amount`,
 1 AS `expected_amount`,
 1 AS `collection_efficiency`,
 1 AS `avg_days_to_pay`*/;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `vw_dashboard_summary`
--

/*!50001 DROP VIEW IF EXISTS `vw_dashboard_summary`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_dashboard_summary` AS select (select count(0) from `members` where (`members`.`membership_status` = 'active')) AS `total_active_members`,(select count(0) from `members` where (`members`.`membership_status` = 'inactive')) AS `total_inactive_members`,round((((select count(0) from `members` where (`members`.`membership_status` = 'active')) * 100.0) / nullif((select count(0) from `members`),0)),2) AS `member_activity_rate`,(select count(distinct `member_amortizations`.`member_id`) from `member_amortizations` where (`member_amortizations`.`status` = 'defaulted')) AS `total_defaulted_members`,(select round(avg(`vw_risk_assessment_dashboard`.`default_rate`),2) from `vw_risk_assessment_dashboard`) AS `average_default_rate`,(select round(avg(`vw_risk_assessment_dashboard`.`percent_below_minimum`),2) from `vw_risk_assessment_dashboard`) AS `avg_accounts_below_minimum_pct`,(select count(0) from `member_amortizations` where (`member_amortizations`.`status` = 'active')) AS `active_loans_count`,(select sum(`member_amortizations`.`remaining_balance`) from `member_amortizations` where (`member_amortizations`.`status` = 'active')) AS `total_outstanding_loans`,(select round(((count((case when (`member_amortizations`.`status` = 'completed') then 1 end)) * 100.0) / nullif(count(0),0)),2) from `member_amortizations`) AS `loan_completion_rate`,(select sum(`members`.`current_balance`) from `members` where (`members`.`membership_status` = 'active')) AS `total_active_balances`,(select round(((sum(`amortization_payments`.`amount`) * 100.0) / nullif((select sum(`member_amortizations`.`monthly_amount`) from `member_amortizations` where (`member_amortizations`.`status` = 'active')),0)),2) from `amortization_payments` where ((month(`amortization_payments`.`payment_date`) = month(curdate())) and (year(`amortization_payments`.`payment_date`) = year(curdate())))) AS `current_month_collection_efficiency`,(select count(0) from `members` where ((month(`members`.`opened_date`) = month(curdate())) and (year(`members`.`opened_date`) = year(curdate())))) AS `new_members_this_month`,(select (count(0) - lag(count(0)) OVER (ORDER BY date_format(`members`.`opened_date`,'%Y-%m') ) ) from `members` where (date_format(`members`.`opened_date`,'%Y-%m') = date_format(curdate(),'%Y-%m')) group by date_format(`members`.`opened_date`,'%Y-%m')) AS `member_growth_from_last_month` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_member_growth_analysis`
--

/*!50001 DROP VIEW IF EXISTS `vw_member_growth_analysis`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_member_growth_analysis` AS select date_format(`members`.`opened_date`,'%Y-%m') AS `month_year`,count(0) AS `new_members`,sum((case when (`members`.`membership_status` = 'active') then 1 else 0 end)) AS `active_members`,sum((case when (`members`.`membership_status` = 'inactive') then 1 else 0 end)) AS `inactive_members`,sum((case when (`members`.`membership_status` = 'suspended') then 1 else 0 end)) AS `suspended_members`,sum((case when (`members`.`membership_status` = 'closed') then 1 else 0 end)) AS `closed_members`,avg(`members`.`current_balance`) AS `avg_opening_balance` from `members` group by date_format(`members`.`opened_date`,'%Y-%m') order by `month_year` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_monthly_receivables_trend`
--

/*!50001 DROP VIEW IF EXISTS `vw_monthly_receivables_trend`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_monthly_receivables_trend` AS with `monthly_metrics` as (select date_format(`ma`.`start_date`,'%Y-%m-01') AS `month_date`,sum(`ma`.`principal_amount`) AS `total_principal`,sum(`ma`.`remaining_balance`) AS `total_remaining`,sum(coalesce(`ap`.`amount`,0)) AS `total_collected`,count(distinct `ma`.`amortization_id`) AS `total_loans`,count(distinct (case when ((`ma`.`status` = 'active') and (curdate() > `ma`.`end_date`)) then `ma`.`amortization_id` end)) AS `overdue_loans` from (`member_amortizations` `ma` left join `amortization_payments` `ap` on(((`ma`.`amortization_id` = `ap`.`amortization_id`) and (date_format(`ap`.`payment_date`,'%Y-%m') = date_format(`ma`.`start_date`,'%Y-%m'))))) group by date_format(`ma`.`start_date`,'%Y-%m-01')) select `monthly_metrics`.`month_date` AS `month_date`,`monthly_metrics`.`total_principal` AS `total_principal`,`monthly_metrics`.`total_remaining` AS `total_remaining`,`monthly_metrics`.`total_collected` AS `total_collected`,`monthly_metrics`.`total_loans` AS `total_loans`,`monthly_metrics`.`overdue_loans` AS `overdue_loans`,round(((`monthly_metrics`.`total_collected` / nullif(`monthly_metrics`.`total_principal`,0)) * 100),2) AS `collection_rate`,round(((`monthly_metrics`.`overdue_loans` / nullif(`monthly_metrics`.`total_loans`,0)) * 100),2) AS `overdue_rate` from `monthly_metrics` order by `monthly_metrics`.`month_date` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_account_details`
--

/*!50001 DROP VIEW IF EXISTS `vw_account_details`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_account_details` AS select `a`.`account_id` AS `account_id`,`a`.`account_uid` AS `account_uid`,`a`.`email` AS `email`,`a`.`username` AS `username`,`a`.`account_status` AS `account_status`,`a`.`email_verified_at` AS `email_verified_at`,`a`.`first_login_at` AS `first_login_at`,`ar`.`role_name` AS `role_name`,`a`.`created_at` AS `created_at`,`a`.`updated_at` AS `updated_at` from (`accounts` `a` join `account_roles` `ar` on((`a`.`role_id` = `ar`.`role_id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_loan_performance_analytics`
--

/*!50001 DROP VIEW IF EXISTS `vw_loan_performance_analytics`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_loan_performance_analytics` AS select `at`.`type_name` AS `loan_type`,count(`ma`.`amortization_id`) AS `total_loans`,sum(`ma`.`principal_amount`) AS `total_principal`,sum(`ma`.`remaining_balance`) AS `total_remaining`,avg(`ma`.`monthly_amount`) AS `avg_monthly_payment`,count((case when (`ma`.`status` = 'completed') then 1 end)) AS `completed_loans`,count((case when (`ma`.`status` = 'defaulted') then 1 end)) AS `defaulted_loans`,count((case when (`ma`.`status` = 'active') then 1 end)) AS `active_loans`,round(((count((case when (`ma`.`status` = 'defaulted') then 1 end)) * 100.0) / count(0)),2) AS `default_rate`,avg((to_days(ifnull((select max(`amortization_payments`.`payment_date`) from `amortization_payments` where (`amortization_payments`.`amortization_id` = `ma`.`amortization_id`)),curdate())) - to_days(`ma`.`start_date`))) AS `avg_loan_duration_days` from (`amortization_types` `at` left join `member_amortizations` `ma` on((`at`.`type_id` = `ma`.`type_id`))) group by `at`.`type_name` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_employee_details`
--

/*!50001 DROP VIEW IF EXISTS `vw_employee_details`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_employee_details` AS select `e`.`employee_id` AS `employee_id`,`e`.`first_name` AS `first_name`,`e`.`middle_name` AS `middle_name`,`e`.`last_name` AS `last_name`,concat(`e`.`first_name`,' ',ifnull(concat(left(`e`.`middle_name`,1),'. '),''),`e`.`last_name`) AS `full_name`,`e`.`contact_number` AS `contact_number`,`e`.`salary` AS `salary`,`e`.`rata` AS `rata`,`a`.`account_uid` AS `account_uid`,`a`.`email` AS `email`,`a`.`account_status` AS `account_status`,`ar`.`role_name` AS `role_name`,`e`.`created_at` AS `created_at`,`e`.`updated_at` AS `updated_at` from ((`employees` `e` left join `accounts` `a` on((`e`.`account_id` = `a`.`account_id`))) left join `account_roles` `ar` on((`a`.`role_id` = `ar`.`role_id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_amortization_payment_summary`
--

/*!50001 DROP VIEW IF EXISTS `vw_amortization_payment_summary`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_amortization_payment_summary` AS select `m`.`member_id` AS `member_id`,`m`.`member_uid` AS `member_uid`,concat(`m`.`first_name`,' ',ifnull(concat(left(`m`.`middle_name`,1),'. '),''),`m`.`last_name`) AS `full_name`,`ma`.`amortization_id` AS `amortization_id`,`at`.`type_name` AS `type_name`,`ma`.`principal_amount` AS `principal_amount`,`ma`.`monthly_amount` AS `monthly_amount`,`ma`.`remaining_balance` AS `remaining_balance`,count(`ap`.`payment_id`) AS `total_payments`,ifnull(sum(`ap`.`amount`),0) AS `total_amount_paid`,round((`ma`.`principal_amount` + ((`ma`.`principal_amount` * (`at`.`interest_rate` / 100)) * (timestampdiff(MONTH,`ma`.`start_date`,`ma`.`end_date`) / 12))),2) AS `total_payable`,(select min(`amortization_payments`.`payment_date`) from `amortization_payments` where (`amortization_payments`.`amortization_id` = `ma`.`amortization_id`)) AS `first_payment_date`,(select max(`amortization_payments`.`payment_date`) from `amortization_payments` where (`amortization_payments`.`amortization_id` = `ma`.`amortization_id`)) AS `last_payment_date`,`ma`.`start_date` AS `start_date`,`ma`.`end_date` AS `end_date`,`ma`.`status` AS `status`,(case when (`ma`.`status` = 'completed') then 'Completed' when ((curdate() > `ma`.`end_date`) and (`ma`.`status` = 'active')) then 'Overdue' when (`ma`.`status` = 'defaulted') then 'Defaulted' else 'Active' end) AS `payment_status` from (((`members` `m` join `member_amortizations` `ma` on((`m`.`member_id` = `ma`.`member_id`))) join `amortization_types` `at` on((`ma`.`type_id` = `at`.`type_id`))) left join `amortization_payments` `ap` on((`ma`.`amortization_id` = `ap`.`amortization_id`))) group by `m`.`member_id`,`m`.`member_uid`,`m`.`first_name`,`m`.`middle_name`,`m`.`last_name`,`ma`.`amortization_id`,`at`.`type_name`,`ma`.`principal_amount`,`ma`.`monthly_amount`,`ma`.`remaining_balance`,`ma`.`start_date`,`ma`.`end_date`,`ma`.`status` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_monthly_balance_trends`
--

/*!50001 DROP VIEW IF EXISTS `vw_monthly_balance_trends`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_monthly_balance_trends` AS select date_format(`mt`.`created_at`,'%Y-%m') AS `month_year`,`mt`.`type_name` AS `account_type`,count(distinct `m`.`member_id`) AS `total_members`,sum(`mt2`.`amount`) AS `total_transactions`,sum((case when (`mt2`.`transaction_type` in ('deposit','interest')) then `mt2`.`amount` else 0 end)) AS `total_credits`,sum((case when (`mt2`.`transaction_type` in ('withdrawal','fee')) then `mt2`.`amount` else 0 end)) AS `total_debits`,avg(`m`.`current_balance`) AS `average_balance` from ((`member_types` `mt` join `members` `m` on((`m`.`type_id` = `mt`.`type_id`))) left join `member_transactions` `mt2` on((`m`.`member_id` = `mt2`.`member_id`))) group by date_format(`mt`.`created_at`,'%Y-%m'),`mt`.`type_name` order by `month_year` desc,`account_type` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_monthly_overdue_metrics`
--

/*!50001 DROP VIEW IF EXISTS `vw_monthly_overdue_metrics`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_monthly_overdue_metrics` AS with `payment_delays` as (select date_format(`ma`.`start_date`,'%Y-%m-01') AS `month_date`,`ma`.`amortization_id` AS `amortization_id`,(case when (`ma`.`status` = 'completed') then 0 when ((`ma`.`status` = 'active') and (curdate() > `ma`.`end_date`)) then (to_days(curdate()) - to_days(`ma`.`end_date`)) else 0 end) AS `days_overdue`,(case when (`ap`.`payment_date` is not null) then (to_days(`ap`.`payment_date`) - to_days((`ma`.`start_date` + interval 1 month))) else 0 end) AS `payment_delay` from (`member_amortizations` `ma` left join (select `amortization_payments`.`amortization_id` AS `amortization_id`,min(`amortization_payments`.`payment_date`) AS `payment_date` from `amortization_payments` group by `amortization_payments`.`amortization_id`) `ap` on((`ma`.`amortization_id` = `ap`.`amortization_id`)))) select `payment_delays`.`month_date` AS `month_date`,count(`payment_delays`.`amortization_id`) AS `total_loans`,round(avg(nullif(`payment_delays`.`days_overdue`,0)),2) AS `avg_days_overdue`,round(avg(nullif(`payment_delays`.`payment_delay`,0)),2) AS `avg_payment_delay`,count((case when (`payment_delays`.`days_overdue` > 0) then 1 end)) AS `overdue_count`,count((case when (`payment_delays`.`payment_delay` > 0) then 1 end)) AS `delayed_payment_count` from `payment_delays` group by `payment_delays`.`month_date` order by `payment_delays`.`month_date` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_dashboard_metrics`
--

/*!50001 DROP VIEW IF EXISTS `vw_dashboard_metrics`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_dashboard_metrics` AS with `active_members_summary` as (select count(0) AS `total_active_members`,sum(coalesce(`members`.`current_balance`,0)) AS `total_active_balances` from `members` where (`members`.`membership_status` = 'active')), `receivables_summary` as (select sum(coalesce(`ma`.`remaining_balance`,0)) AS `total_receivables`,sum((case when ((curdate() > `ma`.`end_date`) and (`ma`.`status` = 'active')) then coalesce(`ma`.`remaining_balance`,0) else 0 end)) AS `overdue_receivables`,count(distinct (case when ((curdate() > `ma`.`end_date`) and (`ma`.`status` = 'active')) then `ma`.`member_id` end)) AS `overdue_accounts`,count(distinct `ma`.`member_id`) AS `total_borrowers` from `member_amortizations` `ma` where (`ma`.`status` = 'active')) select `ams`.`total_active_balances` AS `total_active_balances`,`ams`.`total_active_members` AS `total_active_members`,`rs`.`total_receivables` AS `total_receivables`,`rs`.`total_borrowers` AS `total_borrowers`,`rs`.`overdue_receivables` AS `overdue_receivables`,`rs`.`overdue_accounts` AS `overdue_accounts`,round((case when (`rs`.`total_receivables` > 0) then ((`rs`.`overdue_receivables` / `rs`.`total_receivables`) * 100) else 0 end),2) AS `overdue_percentage` from (`active_members_summary` `ams` join `receivables_summary` `rs`) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_amortization_details`
--

/*!50001 DROP VIEW IF EXISTS `vw_amortization_details`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_amortization_details` AS select `ma`.`amortization_id` AS `amortization_id`,`ma`.`member_id` AS `member_id`,`ma`.`type_id` AS `type_id`,`ma`.`principal_amount` AS `principal_amount`,`ma`.`monthly_amount` AS `monthly_amount`,`ma`.`remaining_balance` AS `remaining_balance`,`ma`.`start_date` AS `start_date`,`ma`.`end_date` AS `end_date`,`ma`.`status` AS `status`,`ma`.`created_at` AS `created_at`,`at`.`type_name` AS `type_name`,`at`.`description` AS `description`,`at`.`interest_rate` AS `interest_rate`,ifnull(sum(`ap`.`amount`),0) AS `total_paid`,(`ma`.`remaining_balance` - ifnull(sum(`ap`.`amount`),0)) AS `balance_due`,`m`.`current_balance` AS `current_balance`,concat(`m`.`first_name`,' ',ifnull(concat(left(`m`.`middle_name`,1),'. '),''),`m`.`last_name`) AS `full_name` from (((`member_amortizations` `ma` join `amortization_types` `at` on((`ma`.`type_id` = `at`.`type_id`))) left join `amortization_payments` `ap` on((`ma`.`amortization_id` = `ap`.`amortization_id`))) join `members` `m` on((`ma`.`member_id` = `m`.`member_id`))) group by `ma`.`amortization_id`,`ma`.`member_id`,`ma`.`type_id`,`ma`.`principal_amount`,`ma`.`monthly_amount`,`ma`.`remaining_balance`,`ma`.`start_date`,`ma`.`end_date`,`ma`.`status`,`ma`.`created_at`,`at`.`type_name`,`at`.`description`,`at`.`interest_rate` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_membership_status_summary`
--

/*!50001 DROP VIEW IF EXISTS `vw_membership_status_summary`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_membership_status_summary` AS select `mt`.`type_name` AS `type_name`,`m`.`membership_status` AS `membership_status`,count(`m`.`member_id`) AS `total_members`,sum(`m`.`current_balance`) AS `total_balance` from (`member_types` `mt` left join `members` `m` on((`mt`.`type_id` = `m`.`type_id`))) group by `mt`.`type_name`,`m`.`membership_status` order by `mt`.`type_name`,`m`.`membership_status` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_daily_transaction_summary`
--

/*!50001 DROP VIEW IF EXISTS `vw_daily_transaction_summary`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_daily_transaction_summary` AS select cast(`member_transactions`.`created_at` as date) AS `transaction_date`,`member_transactions`.`transaction_type` AS `transaction_type`,count(0) AS `transaction_count`,sum(`member_transactions`.`amount`) AS `total_amount`,min(`member_transactions`.`amount`) AS `min_amount`,max(`member_transactions`.`amount`) AS `max_amount`,avg(`member_transactions`.`amount`) AS `avg_amount` from `member_transactions` group by cast(`member_transactions`.`created_at` as date),`member_transactions`.`transaction_type` order by `transaction_date` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_member_details`
--

/*!50001 DROP VIEW IF EXISTS `vw_member_details`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_member_details` AS select `m`.`member_id` AS `member_id`,`m`.`member_uid` AS `member_uid`,`m`.`first_name` AS `first_name`,`m`.`middle_name` AS `middle_name`,`m`.`last_name` AS `last_name`,concat(`m`.`first_name`,' ',ifnull(concat(left(`m`.`middle_name`,1),'. '),''),`m`.`last_name`) AS `full_name`,`m`.`contact_number` AS `contact_number`,`m`.`membership_status` AS `membership_status`,`m`.`current_balance` AS `current_balance`,`m`.`opened_date` AS `opened_date`,`m`.`closed_date` AS `closed_date`,`m`.`house_address` AS `house_address`,`m`.`barangay` AS `barangay`,`m`.`municipality` AS `municipality`,`m`.`province` AS `province`,`m`.`region` AS `region`,concat(`m`.`house_address`,', ',`m`.`barangay`,', ',`m`.`municipality`,', ',`m`.`province`,', ',`m`.`region`) AS `full_address`,`mt`.`type_name` AS `membership_type`,`mt`.`minimum_balance` AS `minimum_balance`,`mt`.`interest_rate` AS `interest_rate`,`a`.`account_uid` AS `account_uid`,`a`.`email` AS `email`,`a`.`account_status` AS `account_status`,`a`.`created_at` AS `registered_at` from ((`members` `m` join `member_types` `mt` on((`m`.`type_id` = `mt`.`type_id`))) left join `accounts` `a` on((`m`.`account_id` = `a`.`account_id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_risk_assessment_dashboard`
--

/*!50001 DROP VIEW IF EXISTS `vw_risk_assessment_dashboard`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_risk_assessment_dashboard` AS select `mt`.`type_name` AS `account_type`,count(distinct `m`.`member_id`) AS `total_accounts`,sum((case when (`m`.`current_balance` < `mt`.`minimum_balance`) then 1 else 0 end)) AS `accounts_below_minimum`,round(((sum((case when (`m`.`current_balance` < `mt`.`minimum_balance`) then 1 else 0 end)) * 100.0) / count(0)),2) AS `percent_below_minimum`,count(distinct (case when (`ma`.`status` = 'defaulted') then `m`.`member_id` end)) AS `members_with_defaults`,round(((count(distinct (case when (`ma`.`status` = 'defaulted') then `m`.`member_id` end)) * 100.0) / count(distinct `m`.`member_id`)),2) AS `default_rate`,avg(`m`.`current_balance`) AS `avg_balance`,min(`m`.`current_balance`) AS `min_balance`,max(`m`.`current_balance`) AS `max_balance` from ((`member_types` `mt` join `members` `m` on((`mt`.`type_id` = `m`.`type_id`))) left join `member_amortizations` `ma` on((`m`.`member_id` = `ma`.`member_id`))) group by `mt`.`type_name` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_member_locations`
--

/*!50001 DROP VIEW IF EXISTS `vw_member_locations`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_member_locations` AS select `members`.`province` AS `province`,`members`.`municipality` AS `municipality`,`members`.`barangay` AS `barangay`,count(`members`.`member_id`) AS `member_count` from `members` group by `members`.`province`,`members`.`municipality`,`members`.`barangay` order by `members`.`province`,`members`.`municipality`,`members`.`barangay` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_active_accounts_summary`
--

/*!50001 DROP VIEW IF EXISTS `vw_active_accounts_summary`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_active_accounts_summary` AS select `mt`.`type_name` AS `type_name`,count(`m`.`member_id`) AS `total_members`,sum(`m`.`current_balance`) AS `total_balance`,min(`m`.`current_balance`) AS `min_balance`,max(`m`.`current_balance`) AS `max_balance`,avg(`m`.`current_balance`) AS `avg_balance` from (`member_types` `mt` left join `members` `m` on(((`mt`.`type_id` = `m`.`type_id`) and (`m`.`membership_status` = 'active')))) group by `mt`.`type_id`,`mt`.`type_name` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_payment_collection_efficiency`
--

/*!50001 DROP VIEW IF EXISTS `vw_payment_collection_efficiency`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_payment_collection_efficiency` AS select `at`.`type_name` AS `loan_type`,year(`ap`.`payment_date`) AS `year`,month(`ap`.`payment_date`) AS `month`,count(distinct `ma`.`amortization_id`) AS `active_loans`,count(`ap`.`payment_id`) AS `payments_made`,sum(`ap`.`amount`) AS `collected_amount`,sum(`ma`.`monthly_amount`) AS `expected_amount`,round(((sum(`ap`.`amount`) * 100.0) / nullif(sum(`ma`.`monthly_amount`),0)),2) AS `collection_efficiency`,avg((to_days(`ap`.`payment_date`) - to_days(`ma`.`start_date`))) AS `avg_days_to_pay` from ((`amortization_types` `at` join `member_amortizations` `ma` on((`at`.`type_id` = `ma`.`type_id`))) left join `amortization_payments` `ap` on((`ma`.`amortization_id` = `ap`.`amortization_id`))) where (`ma`.`status` = 'active') group by `at`.`type_name`,year(`ap`.`payment_date`),month(`ap`.`payment_date`) order by `year` desc,`month` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-06  9:38:58
