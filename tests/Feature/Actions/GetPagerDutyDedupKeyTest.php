<?php

namespace Tests\Feature\Actions;

use App\Actions\GetPagerDutyDedupKey;
use App\Enums\Category;
use App\Models\Monitor;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(GetPagerDutyDedupKey::class)]
class GetPagerDutyDedupKeyTest extends TestCase
{
    /**
     * @see GetPagerDutyDedupKey::handle
     */
    public function test_it_returns_dedup_key_for_uptime_category(): void
    {
        // Collect
        $monitor = Monitor::factory()->createQuietly();
        $category = Category::UPTIME;

        // Act
        $dedupKey = (new GetPagerDutyDedupKey)->handle($monitor, $category);

        // Assert
        $this->assertSame('event-uptime-'.$monitor->hashId, $dedupKey);
    }

    /**
     * @see GetPagerDutyDedupKey::handle
     */
    public function test_it_returns_dedup_key_for_certificate_category(): void
    {
        // Collect
        $monitor = Monitor::factory()->createQuietly();
        $category = Category::CERTIFICATE;

        // Act
        $dedupKey = (new GetPagerDutyDedupKey)->handle($monitor, $category);

        // Assert
        $this->assertSame('event-certificate-'.$monitor->hashId, $dedupKey);
    }
}
