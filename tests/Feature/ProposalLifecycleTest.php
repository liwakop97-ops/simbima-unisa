<?php

namespace Tests\Feature;

use App\Models\ProposalReview;
use PHPUnit\Framework\TestCase;

class ProposalLifecycleTest extends TestCase
{
    public function test_weighted_review_score_calculation(): void
    {
        $score = ProposalReview::hitungNilaiTerbobot(80, 80, 90, 85, 75);
        // (80*0.20) + (80*0.15) + (90*0.25) + (85*0.25) + (75*0.15) = 16 + 12 + 22.5 + 21.25 + 11.25 = 83.0
        $this->assertEquals(83.0, $score);
    }
}
