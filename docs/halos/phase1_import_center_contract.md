# HALOS Phase 1 — Import Center Contract (Stabilization-First)

## Purpose
This document defines the **minimum stable contract** for Phase 1 ingestion into HALOS without introducing new architecture or breaking existing flows.

- Keep existing Laravel + Filament modules intact.
- Reuse current `Order`, `Customer`, queue/risk services.
- Treat Excel import as semi-manual ingestion source.

## Scope (Phase 1 only)
1. Data ingestion from operational Excel files
2. Mapping into existing entities
3. Status normalization and failure-reason normalization
4. Import validation + duplicate detection + import logs

No API integrations, no real-time sync, no AI overhaul.

---

## Reused Existing Modules
- `app/Filament/Pages/HELOSImportCenter.php` (upload + parse + row validation pattern)
- `app/Imports/OrdersImport.php` (row mapping + status conversion)
- `app/Models/Order.php` + `orders` table (core operational order entity)
- `app/Models/Customer.php` + risk/blacklist fields
- `app/Filament/Pages/VerificationQueue.php` (operational queue behavior)
- `app/Services/CustomerIntelligenceService.php`, `RiskScoringService.php`, `CODDecisionService.php`

---

## Canonical Phase 1 Import Field Contract
These are **canonical** internal fields; source columns can vary by client/file and must be mapped.

### Order identity
- `external_order_no`
- `order_date`
- `channel`

### Customer
- `customer_name`
- `phone`
- `address_line`
- `city`

### Order content
- `product_text`
- `cod_amount`

### Operations
- `assigned_employee`
- `operational_status`
- `remarks`
- `courier_name`
- `tracking_no`
- `confirmation_state`
- `failure_reason`
- `delivery_state`

### Context
- `service_or_pipeline`
- `workspace_or_business_unit`

---

## Status Normalization Contract
### Operational Status (required)
Allowed normalized values:
- `pending`
- `delivered`
- `returned`
- `cancelled`

### Failure Reason (optional but critical)
Allowed normalized values:
- `no_answer`
- `fake_order`
- `refused`
- `wrong_address`
- `courier_issue`
- `customer_changed_mind`
- `rescheduled`
- `other`

> Keep status and failure reason separate.

---

## Duplicate Detection Rules (Phase 1)
Row-level duplicate candidate if any are true:
1. same `external_order_no` + same `channel`
2. same normalized `phone` + same `order_date` + same `cod_amount`
3. same normalized `phone` + same `tracking_no`

Flag duplicates for review; do not hard-delete imports automatically.

---

## Validation Baseline
Minimum row validation:
- `phone` required
- at least one of: `external_order_no` or (`phone` + `order_date`)
- `operational_status` must normalize to allowed values
- `cod_amount` numeric if present

Rejected rows should be logged with row number + reason.

---

## Import Logging Baseline
Each import batch should capture:
- file name
- uploader
- timestamp
- total rows
- accepted rows
- rejected rows
- duplicate flagged rows
- mapping profile used

---

## Stability Rules
- Do not replace existing order/risk systems.
- Extend existing import center patterns.
- Keep backward compatibility with current workflows.
- Avoid hardcoded company-specific column names.

---

## Next Step (Implementation-safe)
Implement a mapping profile layer in existing Import Center before introducing new modules.
