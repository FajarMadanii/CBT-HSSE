<?php

namespace Tests\Feature;

use App\Models\Examination;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExaminationAvailabilityTest extends TestCase
{
    use WithFaker;

    public function test_exam_is_available_at_exact_start_and_end_boundaries(): void
    {
        config()->set('app.timezone', 'Asia/Jakarta');

        $base = now()->setTimezone('Asia/Jakarta')->setTime(12, 30, 0);
        $this->travelTo($base);

        $exam = Examination::make([
            'start_at' => $base,
            'end_at' => $base->copy()->addMinutes(90),
        ]);

        $this->assertTrue($exam->isAvailableNow());

        $this->travelTo($base->copy()->addMinutes(90));
        $this->assertTrue($exam->isAvailableNow());
    }
}
