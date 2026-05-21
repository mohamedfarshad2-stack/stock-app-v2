# HELOS Architecture Sign-off and Minimal Stabilization Plan

Date: 2026-05-21
Scope: Architecture review and cleanup sequence only (no feature expansion).

## 1) Architecture review against HELOS philosophy

HELOS should remain an **operational intelligence and profitability command center** for COD/order operations, not a general ERP/accounting suite.

### Profit model to preserve
Expected Operational Profit should be represented as:

- Selling Price
- Product Cost
- Courier Cost
- Return Loss Estimate (system-calculated from return-rate assumptions)
- Packing Cost
- Ad Cost Allocation (derived from monthly ad expense)
- Direct Worker/Product Cost

And explicitly **exclude** full-company salary/admin overhead from per-order/SKU expected profit.

## 2) What is currently aligned

- Core surfaces already exist and are separated for day-to-day operations:
  - HELOSDashboard
  - HELOSImportCenter
  - OrderResource
  - ProductResource / SKU Cost Master
- Finance has dedicated resources for monthly-level business finance:
  - MoneyRecordResource
  - FinanceCategoryResource
- Settings and advanced levers are separated:
  - BusinessUnitResource
  - DailyCODOperationResource
  - CODPerformanceAssumptionResource
- Import/order/dashboard/product flows should be preserved as currently working foundations.

## 3) What is currently misaligned (structural drift risk)

- Navigation/UX may still feel like separate admin modules rather than one HELOS command center.
- Legacy/non-HELOS pages can dilute operator focus and blur canonical HELOS flows.
- Finance can drift into accounting-style scope unless constrained to operational finance inputs + monthly overhead visibility.
- Advanced COD assumptions risk becoming disconnected from where operational users consume impacts (orders/dashboard).

## 4) What should change now vs. what should not be touched

### Change now (stabilization only)

1. Enforce a single HELOS-first navigation posture.
2. Clarify Finance semantics:
   - Finance contributes operational inputs and monthly overhead visibility.
   - Finance should not redefine per-order expected profit using blanket overhead allocation.
3. Keep Advanced assumptions visible but clearly marked as model/assumption controls.
4. De-emphasize legacy pages without deleting or breaking them.

### Do NOT touch now

- No new modules.
- No data-model rewrite.
- No major route/permission re-architecture.
- No changes that risk import, order, dashboard, or product flow stability.

## 5) Minimal stabilization plan (no expansion)

### Phase A — IA/Navigation hardening

- Keep top-level groups exactly:
  - HELOS Core
  - Finance
  - Settings
  - Advanced
- Ensure all HELOS-operational resources are consistently labeled and ordered within these groups.
- Move legacy/non-HELOS surfaces under a clearly secondary grouping (e.g., "Legacy" / "Archive") or deprioritized ordering.

### Phase B — Semantic guardrails (copy + labels only)

- Add/adjust descriptions/help text to reinforce:
  - Expected profit is operational, per-order/SKU-centric.
  - Company-wide overhead remains monthly finance visibility, not forced into SKU margin.
- Keep these guardrails in resource labels, form helper text, and dashboard copy.

### Phase C — Integration sanity checks (static/code review level)

- Confirm Import → Orders → Product cost references → Dashboard metrics flow is still coherent in code structure.
- Confirm Advanced assumption resources are referenced by operational calculations rather than siloed.
- Confirm Finance categories/types cannot accidentally imply full accounting ERP behavior.

## 6) Organization review by area

### Finance
- **Correct placement:** Finance group.
- **Constraint:** Treat as operational finance inputs + monthly visibility.
- **Avoid:** Deep generic accounting semantics.

### Advanced COD Operations
- **Correct placement:** Advanced group.
- **Constraint:** Keep as assumption/config levers feeding operational outcomes.
- **Avoid:** Promoting advanced pages as primary daily workflow.

### Product/SKU Costing
- **Correct placement:** HELOS Core.
- **Constraint:** Keep direct cost components tied to expected operational profit logic.

### Orders
- **Correct placement:** HELOS Core.
- **Constraint:** Preserve lifecycle flow and operational statuses.

### Import Center
- **Correct placement:** HELOS Core.
- **Constraint:** Preserve robust normalization and ingestion behavior.

### Dashboard
- **Correct placement:** HELOS Core.
- **Constraint:** Present operational KPIs + expected profitability context, not generic financial statements.

## 7) Final navigation/grouping strategy (recommended)

1. **HELOS Core** (primary, top)
   - Dashboard
   - Import Center
   - Orders
   - SKU Cost Master (ProductResource)
2. **Finance**
   - Money Records
   - Finance Categories
3. **Settings**
   - Business Units
4. **Advanced**
   - Daily COD Operations
   - COD Performance Assumptions
5. **Legacy/Secondary** (de-emphasized)
   - ClientDashboard
   - ClientSettings
   - VerificationQueue
   - CustomerResource
   - COD System / COD Engine / Order Management / Customer Intelligence surfaces

## 8) Legacy COD/System/Customer Intelligence handling (non-breaking)

- Do not remove routes/resources now.
- Keep accessible for backward compatibility.
- Reduce prominence in navigation and annotate as legacy/secondary where possible.
- Prefer redirecting operator SOP/documentation toward HELOS Core entry points.

## 9) Exact next files to inspect/change first

Priority order (minimal, low-risk):

1. Filament resources/pages defining navigation groups, labels, sort order for all HELOS + legacy surfaces.
2. HELOSDashboard page + Blade view text/labels for operational-profit framing.
3. MoneyRecordResource + FinanceCategoryResource labels/help text/type naming to prevent ERP drift.
4. ProductResource + OrderResource helper text to reinforce direct-cost vs monthly-overhead distinction.
5. Advanced resources (DailyCODOperation/CODPerformanceAssumption) labels/descriptions to clarify assumption role.

## 10) Sign-off summary

- The current system can be stabilized without feature expansion.
- Keep the existing operational foundations.
- Apply IA/labeling guardrails to stop drift into fragmented admin/ERP behavior.
- Sequence changes as minimal copy/navigation hardening first; postpone deeper logic changes until after stability confirmation.
