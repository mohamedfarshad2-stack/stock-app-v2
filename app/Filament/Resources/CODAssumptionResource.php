<?php

namespace App\Filament\Resources;

/**
 * Backward-compatibility alias for older references.
 */
class CODAssumptionResource extends CODPerformanceAssumptionResource
{
    /**
     * Keep legacy class for backward compatibility only.
     * Prevent duplicate sidebar item / active-state conflicts.
     */
    protected static bool $shouldRegisterNavigation = false;
}