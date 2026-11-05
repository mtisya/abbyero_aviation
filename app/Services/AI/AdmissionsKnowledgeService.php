<?php

namespace App\Services\AI;

class AdmissionsKnowledgeService
{
    public function getKnowledge(): string
    {
        return <<<'KNOWLEDGE'
ABBYERO AVIATION LLC — ADMISSIONS KNOWLEDGE

GENERAL
- Abbyero Aviation LLC provides aviation flight training.
- The public flight-school page is /flight-school.
- The application form is on that page at #apply.
- Visitors can submit an enquiry/application through the existing form.
- Submitting an application does not mean automatic admission or enrollment.
- Final enrollment, eligibility, scheduling and any additional requirements
  must be confirmed by Abbyero Aviation staff.

PROGRAMS

1. 30-Day Accelerated Private Pilot (PPL)
- Duration: 30 days.
- Total scheduled flight time: 60 hours.
- Aircraft: Piper PA-28-140 and PA-28R-200.
- PA-28-140: 50 hours.
- PA-28R-200: 10 hours dual.
- Planned flight experience: 41 hours dual and 19 hours solo.
- Package price: USD 14,950.
- Includes 30 days of housing and transportation.
- Training includes night, cross-country and basic instrument training,
  ground and flight briefings, and FAA Private Pilot ACS/checkride preparation.
- Completion depends on proficiency, weather, aircraft availability,
  maintenance and successful completion of required FAA milestones.

2. 30-Day Accelerated Instrument Rating
- Duration: 30 days.
- Flight training: 40 hours dual instruction.
- Aircraft: Piper PA-28-140.
- Published package price: USD 9,999.
- Accommodation: 30 days included.
- Includes FAA Instrument Rating ACS preparation.
- Eligibility and prior aeronautical experience must be confirmed by the
  admissions/training team.

3. 30-Day Accelerated Commercial Pilot (CPL)
- Duration: 30 days.
- Location: El Paso, Texas.
- Aircraft: PA-28-140 / PA-28R-200.
- Flight training: 40–50 hours.
- Published enrollment experience reference: 200 hours.
- 40-hour package: USD 9,999.
- 50-hour plus complex package: USD 12,199.
- Includes commercial maneuvers, cross-country, night operations,
  instrument proficiency and FAA Commercial Pilot ACS preparation.
- Individual eligibility and prior flight experience require staff review.

4. 15-Day Accelerated Multi-Engine Training
- Aircraft: Cessna 310J.
- Duration: 15 days.
- Dual training: approximately 10–15 flight hours.
- Training is proficiency-based.
- FAA reference: 14 CFR §61.63(c). The program information states
  that the FAA does not specify a minimum flight-training-hour
  requirement for a pilot adding an aircraft class rating to an
  existing certificate. The appropriate instructor endorsement
  and practical test are required.
- Training objectives include aircraft familiarization, multiengine
  aerodynamics, normal and crosswind takeoffs and landings, aircraft
  systems and limitations, performance, weight and balance,
  critical-engine concepts, VMC, engine-failure recognition,
  single-engine performance, emergency operations and practical-test
  preparation.
- Training schedule:
  Days 1–2: Cessna 310J systems, familiarization and normal operations
  (1.0–1.5 hours).
  Days 3–4: Takeoffs, landings and multiengine maneuvering
  (1.5–2.0 hours).
  Days 5–6: Multiengine aerodynamics, VMC and critical engine
  (1.5–2.0 hours).
  Days 7–8: Engine-failure topics, feathering and restart procedures
  (1.5–2.0 hours).
  Days 9–10: Single-engine maneuvering and performance
  (1.0–1.5 hours).
  Days 11–12: Single-engine approaches and emergency operations
  (1.0–1.5 hours).
  Day 13: Advanced emergency scenarios (1.0–1.5 hours).
  Day 14: ACS practical-test preparation (0.5–1.5 hours).
  Day 15: Mock checkride and final proficiency training
  (1.0–1.5 hours).
- Ground training includes Cessna 310J systems, engines and propellers,
  fuel, electrical and landing-gear systems, multiengine aerodynamics,
  critical-engine principles, VMC, single-engine performance,
  weight and balance, takeoff and landing performance, emergency
  procedures, aeronautical decision-making and FAA ACS preparation.
- Flight training rate: USD 410 per hour.
- Accommodation: USD 70 per day.
- 15-day accommodation total: USD 1,050.
- Published package prices:
  10 hours: USD 5,150.
  12 hours: USD 5,970.
  13 hours: USD 6,380.
  14 hours: USD 6,790.
  15 hours: USD 7,200.
- Estimated package range: USD 5,150–7,200.
- Package includes 10–15 hours dual instruction, the 15-day accelerated
  program, Cessna 310J training and accommodation.
- FAA/DPE practical-test fees, checkride aircraft time, transportation,
  meals and training beyond 15 hours are not included unless specifically
  incorporated into the package.
- Actual hours depend on student proficiency, weather, maintenance
  availability and instructor evaluation.
- Confirm availability and the final quotation with Abbyero Aviation.
OTHER PROGRAMS
The application form also lists:
- 15-Day Accelerated Military Pilot Rotary-Wing to Fixed-Wing.
- 30-Day Accelerated Commercial Pilot 100-Hour Time Building.
- Other Aviation Training.
Detailed pricing and eligibility for these options must be confirmed by staff.

APPLICATION PROCESS
- The visitor can use the application form at /flight-school#apply.
- The form asks for full name, email, phone number and program of interest.
- Preferred start date and additional message are optional.
- After submission, the existing system sends an email with a secure onboarding link.
- The AI assistant must never collect application details in chat or submit
  an application on the visitor's behalf.
- The AI assistant must never approve, reject or assess an applicant.

IMPORTANT LIMITATIONS
- Do not invent admission requirements, prices, discounts, aircraft availability,
  start dates, visa guidance, accommodation arrangements or guarantees.
- Do not treat general FAA requirements as confirmation of an individual's
  eligibility.
- If information is missing or uncertain, say so and recommend contacting
  Abbyero Aviation staff through the website.
KNOWLEDGE;
    }
}