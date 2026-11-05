<?php

namespace App\Services\AI;

class AdmissionsAssistantService
{
    public function __construct(
        private AdmissionsKnowledgeService $knowledgeService
    ) {
    }

    /**
     * Generate a response using PHP rules and confirmed Abbyero knowledge.
     */
    public function reply(string $message, array $history = []): string
    {
        $normalized = $this->normalize($message);

        if ($normalized === '') {
            return 'Please enter a question about Abbyero Aviation training programs.';
        }

        // Do not expose internal instructions or configuration.
        if ($this->matches($normalized, [
            'system prompt',
            'api key',
            'secret key',
            'internal configuration',
            'show your instructions',
            'reveal your prompt',
        ])) {
            return 'I can help with Abbyero Aviation programs, training information, fees and the application process. I cannot provide internal system instructions or configuration.';
        }

        if ($this->isGreeting($normalized)) {
            return 'Hello! Welcome to Abbyero Aviation. I can help you learn about our flight training programs, fees, training duration and application process. What would you like to know?';
        }

        if ($this->isProgramCountQuestion($normalized)) {
            return $this->programCountResponse();
        }

        if ($this->isProgramListQuestion($normalized)) {
            return $this->programListResponse();
        }

        $program = $this->identifyProgram($message);

        // Use recent conversation context for questions such as:
        // "How much is it?" or "How long does it take?"
        if ($program === null) {
            $program = $this->identifyProgramFromHistory($history);
        }

        if ($this->isApplicationQuestion($normalized)) {
            return $this->applicationResponse();
        }

        if ($this->isEligibilityQuestion($normalized)) {
            return $this->eligibilityResponse($program);
        }
        // Detailed information from the 30-Day PPL program modal.
if ($program === 'ppl') {
    if ($this->matches($normalized, [
        '30 day schedule',
        'training schedule',
        'schedule breakdown',
        'day by day',
        'what happens each day',
        'training phases',
        '30 days',
    ])) {
        return $this->pplScheduleResponse();
    }

    if ($this->matches($normalized, [
        'faa requirements',
        'faa minimum',
        'faa minimums',
        '61.109',
        'part 61',
        'regulatory requirements',
        'private pilot requirements',
    ])) {
        return $this->pplFaaRequirementsResponse();
    }

    if ($this->matches($normalized, [
        'solo requirements',
        'solo cross country',
        'solo experience',
        'solo flying requirements',
    ])) {
        return $this->pplSoloRequirementsResponse();
    }

    if ($this->matches($normalized, [
        'arrow training',
        'arrow ii',
        'pa-28r-200 training',
        'complex aircraft',
        'retractable landing gear',
        'constant speed propeller',
        'advanced aircraft',
    ])) {
        return $this->pplArrowTrainingResponse();
    }

    if ($this->matches($normalized, [
        'training areas',
        'flight maneuvers',
        'what will i learn',
        'flying maneuvers',
        'flight syllabus',
        'flight training topics',
    ])) {
        return $this->pplTrainingAreasResponse();
    }

    if ($this->matches($normalized, [
        'ground training',
        'ground school',
        'ground instruction',
        'ground lessons',
        'ground briefing',
    ])) {
        return $this->pplGroundTrainingResponse();
    }

    if ($this->matches($normalized, [
        'dual and solo',
        'dual instruction',
        'solo hours',
        'flight allocation',
        'flight hour breakdown',
        'hours breakdown',
        'how are the hours divided',
    ])) {
        return $this->pplFlightStructureResponse();
    }

    if ($this->matches($normalized, [
        'not included',
        'extra fees',
        'additional fees',
        'separate fees',
        'checkride fee',
        'exam fee',
        'medical examination',
        'dpe fee',
    ])) {
        return $this->pplAdditionalFeesResponse();
    }

    if ($this->matches($normalized, [
        'tell me everything',
        'full details',
        'complete details',
        'explain the entire program',
        'more details about the program',
        'detailed information',
    ])) {
        return $this->pplFullDetailsResponse();
    }
}
// Detailed information from the 15-Day Multi-Engine modal.
if ($program === 'multi_engine') {

    if ($this->matches($normalized, [
        'training schedule',
        '15 day schedule',
        'schedule breakdown',
        'day by day',
        'training phases',
    ])) {
        return $this->multiEngineScheduleResponse();
    }

    if ($this->matches($normalized, [
        'training objectives',
        'training topics',
        'what will i learn',
        'what will i be taught',
        'multiengine aerodynamics',
        'critical engine',
        'vmc',
        'engine failure',
        'single engine operations',
    ])) {
        return $this->multiEngineObjectivesResponse();
    }

    if ($this->matches($normalized, [
        'ground training',
        'ground school',
        'ground instruction',
        'ground lessons',
        'ground topics',
    ])) {
        return $this->multiEngineGroundTrainingResponse();
    }

    if ($this->matches($normalized, [
        'faa requirements',
        'faa regulation',
        '61.63',
        'class rating',
        'multiengine rating requirements',
        'multi engine rating requirements',
        'practical test',
        'endorsement',
    ])) {
        return $this->multiEngineFaaResponse();
    }

    if ($this->matches($normalized, [
        'package breakdown',
        'cost breakdown',
        'cost packages',
        '10 hour package',
        '12 hour package',
        '13 hour package',
        '14 hour package',
        '15 hour package',
        'accommodation cost',
        'accommodation fee',
    ])) {
        return $this->multiEngineCostBreakdownResponse();
    }

    if ($this->matches($normalized, [
        'what is included',
        'what does the package include',
        'package includes',
        'what do i get',
        'what is covered',
    ])) {
        return $this->multiEngineIncludedResponse();
    }

    if ($this->matches($normalized, [
        'not included',
        'extra fees',
        'additional fees',
        'checkride fees',
        'dpe fees',
        'transportation cost',
        'meals',
        'excluded',
    ])) {
        return $this->multiEngineExclusionsResponse();
    }

    if ($this->matches($normalized, [
        'proficiency based',
        'how is training assessed',
        'training completion',
        'how do i complete',
        'completion requirements',
    ])) {
        return $this->multiEngineCompletionResponse();
    }

    if ($this->matches($normalized, [
        'tell me everything',
        'full details',
        'complete details',
        'entire program',
        'detailed information',
    ])) {
        return $this->multiEngineFullDetailsResponse();
    }
}

        if ($this->isPriceQuestion($normalized)) {
            return $this->priceResponse($program);
        }

        if ($this->isDurationQuestion($normalized)) {
            return $this->durationResponse($program);
        }

        if ($this->isFlightHoursQuestion($normalized)) {
            return $this->flightHoursResponse($program);
        }

        if ($this->isAircraftQuestion($normalized)) {
            return $this->aircraftResponse($program);
        }

        if ($this->isAccommodationQuestion($normalized)) {
            return $this->accommodationResponse($program);
        }

        if ($this->isLocationQuestion($normalized)) {
            return $this->locationResponse($program);
        }

        if ($this->isIncludedServicesQuestion($normalized)) {
            return $this->includedServicesResponse($program);
        }

        if ($program !== null) {
            return $this->programOverview($program);
        }

        return $this->fallbackResponse();
    }

    private function normalize(string $message): string
    {
        $message = mb_strtolower(trim($message));

        $message = preg_replace('/[^\p{L}\p{N}\s$]/u', ' ', $message);

        return trim(preg_replace('/\s+/', ' ', $message));
    }

    private function matches(string $message, array $keywords): bool
    {
        foreach ($keywords as $keyword) {
            if (str_contains($message, $keyword)) {
                return true;
            }
        }

        return false;
    }

    private function isGreeting(string $message): bool
    {
        return preg_match(
            '/^(hi|hello|hey|good morning|good afternoon|good evening|how are you)\b/',
            $message
        ) === 1;
    }

    private function isProgramCountQuestion(string $message): bool
    {
        return (
            $this->matches($message, [
                'how many programs',
                'how many courses',
                'number of programs',
                'number of courses',
                'programs do you have',
                'courses do you have',
                'programs are available',
                'courses are available',
            ])
        );
    }

    private function isProgramListQuestion(string $message): bool
    {
        return $this->matches($message, [
            'what programs',
            'which programs',
            'list the programs',
            'list your programs',
            'training programs',
            'courses offered',
            'what courses',
            'programs offered',
            'available courses',
            'available programs',
        ]);
    }

    private function isApplicationQuestion(string $message): bool
    {
        return $this->matches($message, [
            'how do i apply',
            'how to apply',
            'application process',
            'apply for',
            'submit an application',
            'application form',
            'where can i apply',
            'how can i enroll',
            'how do i enroll',
            'how to enroll',
            'register for',
            'registration process',
        ]);
    }

    private function isEligibilityQuestion(string $message): bool
    {
        return $this->matches($message, [
            'eligibility',
            'admission requirement',
            'admission requirements',
            'entry requirement',
            'entry requirements',
            'qualification',
            'qualifications',
            'who can apply',
            'can i join',
            'can i qualify',
            'prerequisite',
            'prerequisites',
            'minimum hours',
            'flight experience required',
            'experience do i need',
        ]);
    }

    private function isPriceQuestion(string $message): bool
    {
        return $this->matches($message, [
            'how much',
            'price',
            'pricing',
            'cost',
            'fee',
            'fees',
            'package',
            'charges',
            'tuition',
            'usd',
            'dollar',
            'expensive',
            'payment',
        ]);
    }

    private function isDurationQuestion(string $message): bool
    {
        return $this->matches($message, [
            'how long',
            'duration',
            'how many days',
            'length of the program',
            'length of training',
            'training period',
            'time does it take',
            'days does it take',
            'weeks does it take',
        ]);
    }

    private function isFlightHoursQuestion(string $message): bool
    {
        return $this->matches($message, [
            'flight hours',
            'flying hours',
            'flight time',
            'hours of flight',
            'hours of flying',
            'dual hours',
            'solo hours',
            'how many hours',
            'training hours',
        ]);
    }

    private function isAircraftQuestion(string $message): bool
    {
        return $this->matches($message, [
            'which aircraft',
            'what aircraft',
            'airplane',
            'aircraft',
            'plane do you use',
            'planes do you use',
            'cessna',
            'piper',
        ]);
    }

    private function isAccommodationQuestion(string $message): bool
    {
        return $this->matches($message, [
            'accommodation',
            'accommodations',
            'housing',
            'where will i stay',
            'place to stay',
            'hotel',
            'lodging',
            'transportation',
            'transport',
        ]);
    }

    private function isLocationQuestion(string $message): bool
    {
        return $this->matches($message, [
            'where are you',
            'where is abbyero',
            'training location',
            'training take place',
            'location',
            'which city',
            'which country',
            'el paso',
        ]);
    }

    private function isIncludedServicesQuestion(string $message): bool
    {
        return $this->matches($message, [
            'what is included',
            'what does it include',
            'what do i get',
            'package includes',
            'included in the package',
            'what is covered',
            'benefits',
        ]);
    }

    /**
     * Identify a program from the current message.
     */
    private function identifyProgram(string $message): ?string
    {
        $message = $this->normalize($message);

        // Check more specific programs before general CPL/PPL matches.
        if ($this->matches($message, [
            'military pilot',
            'rotary wing',
            'rotary to fixed',
            'helicopter to fixed',
        ])) {
            return 'military';
        }

        if ($this->matches($message, [
            '100 hour time building',
            '100-hour time building',
            'time building',
            'timebuilding',
        ])) {
            return 'time_building';
        }

        if ($this->matches($message, [
            'multi engine',
            'multi-engine',
            'multi engine rating',
            'cessna 310',
            'multiengine',
        ])) {
            return 'multi_engine';
        }

        if ($this->matches($message, [
            'instrument rating',
            'instrument training',
            'instrument',
        ])) {
            return 'instrument';
        }

        if ($this->matches($message, [
            'commercial pilot',
            'commercial license',
            'commercial licence',
            'commercial rating',
            'cpl',
        ])) {
            return 'cpl';
        }

        if ($this->matches($message, [
            'private pilot',
            'private pilot license',
            'private pilot licence',
            'ppl',
        ])) {
            return 'ppl';
        }

        if ($this->matches($message, [
            'other aviation training',
        ])) {
            return 'other';
        }

        return null;
    }

    /**
     * Look backwards through recent messages for the most recently
     * discussed program.
     */
    private function identifyProgramFromHistory(array $history): ?string
    {
        foreach (array_reverse(array_slice($history, -10)) as $item) {
            if (
                isset($item['content']) &&
                is_string($item['content'])
            ) {
                $program = $this->identifyProgram($item['content']);

                if ($program !== null) {
                    return $program;
                }
            }
        }

        return null;
    }

    private function isKnownProgram(?string $program): bool
    {
        return in_array($program, [
            'ppl',
            'instrument',
            'cpl',
            'multi_engine',
            'military',
            'time_building',
            'other',
        ], true);
    }

    private function programCountResponse(): string
    {
        return "The application form lists six named training options:\n\n"
            . "1. 30-Day Accelerated Private Pilot (PPL)\n"
            . "2. 30-Day Accelerated Instrument Rating\n"
            . "3. 30-Day Accelerated Commercial Pilot (CPL)\n"
            . "4. 15-Day Accelerated Multi-Engine Training\n"
            . "5. 15-Day Accelerated Military Pilot Rotary-Wing to Fixed-Wing\n"
            . "6. 30-Day Accelerated Commercial Pilot 100-Hour Time Building\n\n"
            . "There is also an 'Other Aviation Training' option for enquiries "
            . "about additional training. Some program details and prices need "
            . "confirmation from the Abbyero Aviation team.";
    }

    private function programListResponse(): string
    {
        return $this->programCountResponse()
            . "\n\nI can also provide details about the duration, flight hours "
            . "and published fees for the programs with available information.";
    }

    private function programOverview(?string $program): string
    {
        switch ($program) {
            case 'ppl':
                return "The 30-Day Accelerated Private Pilot (PPL) program "
                    . "provides 60 scheduled flight hours using the Piper "
                    . "PA-28-140 and PA-28R-200. The published package price "
                    . "is USD 14,950, including 30 days of housing and "
                    . "transportation. Completion depends on proficiency, "
                    . "weather, aircraft availability, maintenance and FAA "
                    . "milestones.";

            case 'instrument':
                return "The 30-Day Accelerated Instrument Rating program "
                    . "includes 40 hours of dual instruction in a Piper "
                    . "PA-28-140. The published package price is USD 9,999, "
                    . "with 30 days of accommodation included. Eligibility "
                    . "and prior flight experience must be confirmed by staff.";

            case 'cpl':
                return "The 30-Day Accelerated Commercial Pilot (CPL) program "
                    . "is conducted in El Paso, Texas, using PA-28-140 and "
                    . "PA-28R-200 aircraft. Training is 40–50 flight hours. "
                    . "Published prices are USD 9,999 for the 40-hour package "
                    . "and USD 12,199 for the 50-hour plus complex package. "
                    . "Individual eligibility requires staff review.";

            case 'multi_engine':
                return "The 15-Day Accelerated Multi-Engine Training program "
                    . "uses a Cessna 310J and includes 10–15 published flight "
                    . "hours. The training rate is USD 410 per hour, and "
                    . "accommodation is USD 70 per day. Contact Abbyero for "
                    . "availability and a final quotation.";

            case 'military':
                return "The application form lists a 15-Day Accelerated "
                    . "Military Pilot Rotary-Wing to Fixed-Wing program. "
                    . "Detailed pricing and eligibility must be confirmed "
                    . "by Abbyero Aviation staff.";

            case 'time_building':
                return "The application form lists a 30-Day Accelerated "
                    . "Commercial Pilot 100-Hour Time Building option. "
                    . "Please contact Abbyero Aviation staff to confirm "
                    . "the current details, pricing and eligibility.";

            case 'other':
                return "Abbyero Aviation also accepts enquiries for other "
                    . "aviation training. Please use the application form "
                    . "and select 'Other Aviation Training' so the team can "
                    . "confirm the available options.";

            default:
                return $this->fallbackResponse();
        }
    }

    private function priceResponse(?string $program): string
    {
        switch ($program) {
            case 'ppl':
                return "The published 30-Day Accelerated PPL package price "
                    . "is USD 14,950. It includes 30 days of housing and "
                    . "transportation.";

            case 'instrument':
                return "The published 30-Day Accelerated Instrument Rating "
                    . "package price is USD 9,999. It includes 30 days of "
                    . "accommodation.";

            case 'cpl':
                return "The published 30-Day Accelerated CPL prices are:\n\n"
                    . "- 40-hour package: USD 9,999.\n"
                    . "- 50-hour plus complex package: USD 12,199.\n\n"
                    . "Individual eligibility and the applicable package "
                    . "should be confirmed with the training team.";

           case 'multi_engine':
                return "The 15-Day Accelerated Multi-Engine Training package "
                    . "costs between USD 5,150 and USD 7,200, depending on "
                    . "the selected flight-hour package.\n\n"
                    . "- 10 hours: USD 5,150.\n"
                    . "- 12 hours: USD 5,970.\n"
                    . "- 13 hours: USD 6,380.\n"
                    . "- 14 hours: USD 6,790.\n"
                    . "- 15 hours: USD 7,200.\n\n"
                    . "The package includes USD 1,050 accommodation for 15 days. "
                    . "Flight training is charged at USD 410 per hour.";

            case 'military':
            case 'time_building':
            case 'other':
                return "A confirmed price for this training option is not "
                    . "available in the published information. Please contact "
                    . "Abbyero Aviation staff for an accurate quotation.";

            default:
                return "Which program are you interested in: PPL, Instrument "
                    . "Rating, Commercial Pilot (CPL), or Multi-Engine Training? "
                    . "I can provide the published price for each.";
        }
    }

    private function durationResponse(?string $program): string
    {
        switch ($program) {
            case 'ppl':
            case 'instrument':
            case 'cpl':
                return "The published duration for this program is 30 days. "
                    . "Actual completion depends on training progress, weather, "
                    . "aircraft availability, maintenance and required FAA "
                    . "milestones.";

            case 'multi_engine':
                return "The published Multi-Engine Training duration is "
                    . "15 days.";

            case 'military':
                return "The application form lists this as a 15-day program. "
                    . "Please confirm the current schedule with Abbyero Aviation.";

            case 'time_building':
                return "The application form lists this as a 30-day program. "
                    . "Please confirm the current schedule and details with "
                    . "Abbyero Aviation staff.";

            case 'other':
                return "The duration depends on the type of aviation training. "
                    . "Please contact Abbyero Aviation staff to confirm.";

            default:
                return "The published programs include 30-day PPL, Instrument "
                    . "Rating and CPL training, as well as 15-day Multi-Engine "
                    . "Training. Which program would you like to know about?";
        }
    }

    private function flightHoursResponse(?string $program): string
    {
        switch ($program) {
            case 'ppl':
                return "The PPL program includes 60 scheduled flight hours:\n\n"
                    . "- 50 hours in the PA-28-140.\n"
                    . "- 10 dual hours in the PA-28R-200.\n"
                    . "- Planned experience: 41 dual hours and 19 solo hours.";

            case 'instrument':
                return "The Instrument Rating program includes 40 hours "
                    . "of dual flight instruction in a Piper PA-28-140.";

            case 'cpl':
                return "The CPL program includes 40–50 flight hours. The "
                    . "published packages are a 40-hour package and a "
                    . "50-hour plus complex package.";

            case 'multi_engine':
                return "The Multi-Engine Training program lists 10–15 flight "
                    . "hours using a Cessna 310J.";

            case 'military':
            case 'time_building':
            case 'other':
                return "The detailed flight-hour allocation for this option "
                    . "is not available in the published information. "
                    . "Please confirm with Abbyero Aviation staff.";

            default:
                return "Which program would you like flight-hour details for: "
                    . "PPL, Instrument Rating, CPL, or Multi-Engine Training?";
        }
    }

    private function aircraftResponse(?string $program): string
    {
        switch ($program) {
            case 'ppl':
                return "The PPL program uses the Piper PA-28-140 for 50 hours "
                    . "and the Piper PA-28R-200 for 10 dual hours.";

            case 'instrument':
                return "The Instrument Rating program uses the Piper "
                    . "PA-28-140.";

            case 'cpl':
                return "The CPL program uses the PA-28-140 and PA-28R-200.";

            case 'multi_engine':
                return "The Multi-Engine Training program uses the Cessna 310J.";

            default:
                return "The published training aircraft include the Piper "
                    . "PA-28-140, Piper PA-28R-200 and Cessna 310J. Tell me "
                    . "which program interests you for its aircraft details.";
        }
    }

    private function accommodationResponse(?string $program): string
    {
        switch ($program) {
            case 'ppl':
                return "The PPL package includes 30 days of housing and "
                    . "transportation.";

            case 'instrument':
                return "The Instrument Rating package includes 30 days "
                    . "of accommodation.";

            case 'multi_engine':
                return "Multi-Engine Training accommodation is published "
                    . "at USD 70 per day. Confirm the arrangement and final "
                    . "quotation with Abbyero Aviation.";

            case 'cpl':
                return "The published CPL program information does not specify "
                    . "an accommodation or transportation inclusion. Please "
                    . "confirm these arrangements with Abbyero Aviation staff.";

            default:
                return "Accommodation arrangements vary by program. The PPL "
                    . "package includes 30 days of housing and transportation, "
                    . "the Instrument Rating package includes 30 days of "
                    . "accommodation, and Multi-Engine accommodation is "
                    . "published at USD 70 per day.";
        }
    }

    private function locationResponse(?string $program): string
    {
        if ($program === 'cpl') {
            return "The published 30-Day Accelerated CPL program is conducted "
                . "in El Paso, Texas.";
        }

        return "The published CPL program location is El Paso, Texas. "
            . "For the location and training arrangements of other programs, "
            . "please confirm with Abbyero Aviation staff.";
    }

    private function includedServicesResponse(?string $program): string
    {
        switch ($program) {
            case 'ppl':
                return $this->pplIncludedResponse();

            case 'instrument':
                return "The Instrument Rating package includes 40 hours of "
                    . "dual instruction, 30 days of accommodation and FAA "
                    . "Instrument Rating ACS preparation.";

            case 'cpl':
                return "The CPL training includes commercial maneuvers, "
                    . "cross-country, night operations, instrument proficiency "
                    . "and FAA Commercial Pilot ACS preparation.";

            case 'multi_engine':
                return $this->multiEngineIncludedResponse();

            default:
                return $this->programOverview($program);
        }
    }

    private function eligibilityResponse(?string $program): string
    {
        if ($program === 'instrument') {
            return "Eligibility and prior aeronautical experience for the "
                . "Instrument Rating program must be confirmed by the "
                . "Abbyero admissions/training team.";
        }

        if ($program === 'cpl') {
            return "The published CPL information references 200 hours of "
                . "flight experience for enrollment. However, individual "
                . "eligibility and prior experience must be reviewed by "
                . "Abbyero Aviation staff.";
        }

        return "I cannot confirm an individual's admission eligibility or "
            . "provide unlisted entry requirements. Requirements depend on "
            . "the training program and must be confirmed by Abbyero Aviation "
            . "staff. You can submit an enquiry through the existing "
            . "application form.";
    }

    private function applicationResponse(): string
    {
        return "You can submit an enquiry/application using the existing "
            . "Abbyero Aviation application form:\n\n"
            . $this->applicationUrl()
            . "\n\nSelect your program and complete the form. After submission, "
            . "the existing system sends an email with a secure onboarding "
            . "link. Submitting the form does not guarantee admission or "
            . "enrollment. The Abbyero team must confirm your eligibility "
            . "and next steps.";
    }

    private function fallbackResponse(): string
    {
        return "I can help with Abbyero Aviation admissions information. "
            . "You can ask me about:\n\n"
            . "- Available flight training programs.\n"
            . "- Program prices and duration.\n"
            . "- Flight hours and training aircraft.\n"
            . "- Accommodation and included services.\n"
            . "- Eligibility information.\n"
            . "- How to submit an application.\n\n"
            . "For details that are not published or need individual review, "
            . "I will direct you to the Abbyero Aviation team.";
    }

    private function applicationUrl(): string
    {
        return url('/flight-school#apply');
    }
    private function pplFlightStructureResponse(): string
{
    return "The 30-Day Accelerated PPL program provides 60 total scheduled flight hours.\n\n"
        . "Piper PA-28-140 — 50 hours:\n"
        . "- 31 hours dual instruction.\n"
        . "- 19 hours solo flying.\n\n"
        . "Piper PA-28R-200 Arrow II — 10 hours:\n"
        . "- 10 hours dual instruction.\n\n"
        . "Overall program allocation:\n"
        . "- 41 hours dual instruction.\n"
        . "- 19 hours solo flying.\n"
        . "- 60 total hours.\n\n"
        . "The exact dual/solo allocation may change according to student proficiency "
        . "while maintaining applicable FAA requirements.";
}

private function pplArrowTrainingResponse(): string
{
    return "The PPL program includes 10 hours of dual instruction in the Piper "
        . "Arrow II PA-28R-200.\n\n"
        . "This is an enhanced Abbyero Aviation training component. It is not "
        . "an FAA requirement for obtaining a Private Pilot certificate.\n\n"
        . "The advanced training introduces:\n"
        . "- Retractable landing gear.\n"
        . "- Constant-speed propeller.\n"
        . "- Propeller and power management.\n"
        . "- Gear-extension procedures.\n"
        . "- Emergency gear-extension procedures.\n"
        . "- Complex-aircraft checklists.\n"
        . "- Advanced takeoffs and landings.\n"
        . "- Aircraft performance management.\n"
        . "- Cross-country operations.\n"
        . "- Introduction to commercial-level aircraft management.";
}

private function pplScheduleResponse(): string
{
    return "The published 30-Day PPL training schedule is divided into these phases:\n\n"
        . "Days 1–5: Fundamentals, aircraft control, takeoffs and landings — 10 hours.\n\n"
        . "Days 6–10: Maneuvers, emergencies and initial solo — 10 hours.\n\n"
        . "Days 11–15: Navigation, cross-country and solo development — 10 hours.\n\n"
        . "Days 16–20: Night, instrument and cross-country training — 10 hours.\n\n"
        . "Days 21–24: Solo cross-country and PPL ACS proficiency — 10 hours.\n\n"
        . "Days 25–27: PA-28R-200 complex-aircraft training — 6 hours.\n\n"
        . "Days 28–29: Advanced PA-28R-200 training — 4 hours.\n\n"
        . "Day 30: Final review, checkride preparation and weather reserve.\n\n"
        . "Total: 60 scheduled flight hours. Actual scheduling can change due "
        . "to weather, maintenance, student progress and FAA solo requirements.";
}

private function pplFaaRequirementsResponse(): string
{
    return "The PPL program information references 14 CFR §61.109(a), "
        . "which sets minimum aeronautical experience requirements for "
        . "Private Pilot Airplane Single-Engine Land applicants.\n\n"
        . "The requirements listed in the program modal include:\n"
        . "- 40 hours total flight time.\n"
        . "- 20 hours of flight training from an authorized instructor.\n"
        . "- 10 hours solo flight time.\n"
        . "- 3 hours of cross-country flight training.\n"
        . "- 3 hours of night flight training.\n"
        . "- Night cross-country experience exceeding 100 NM total distance.\n"
        . "- 10 night takeoffs and 10 full-stop landings.\n"
        . "- 3 hours of flight solely by reference to instruments.\n"
        . "- 3 hours of practical-test preparation within the prescribed period.\n\n"
        . "Abbyero's program provides 60 scheduled flight hours. However, "
        . "students must still satisfy each applicable FAA requirement. "
        . "The training team should confirm the requirements applicable "
        . "to an individual student's circumstances.";
}

private function pplSoloRequirementsResponse(): string
{
    return "The PPL program modal lists the following solo experience requirements:\n\n"
        . "- At least 5 hours of solo cross-country flying.\n"
        . "- One 150-NM solo cross-country flight.\n"
        . "- Full-stop landings at three points.\n"
        . "- At least one segment exceeding 50 NM straight-line distance.\n"
        . "- Three takeoffs and three full-stop landings at an airport "
        . "with an operating control tower.\n\n"
        . "Students must satisfy the applicable FAA requirements and "
        . "their instructor's authorization before conducting solo flights.";
}

private function pplTrainingAreasResponse(): string
{
    return "The PPL flight-training areas listed in the program include:\n\n"
        . "- Preflight planning and inspections.\n"
        . "- Aircraft systems.\n"
        . "- Normal and crosswind takeoffs and landings.\n"
        . "- Traffic-pattern operations.\n"
        . "- Slow flight.\n"
        . "- Power-on and power-off stalls.\n"
        . "- Steep turns.\n"
        . "- Ground-reference maneuvers.\n"
        . "- Emergency procedures.\n"
        . "- Navigation and radio communications.\n"
        . "- Cross-country flight planning.\n"
        . "- Aviation weather.\n"
        . "- Night operations.\n"
        . "- Basic instrument flying.\n"
        . "- Lost procedures and diversions.\n"
        . "- Aeronautical decision-making and risk management.\n"
        . "- FAA Private Pilot ACS preparation.";
}

private function pplGroundTrainingResponse(): string
{
    return "Ground training and briefings support the PPL flight syllabus.\n\n"
        . "The published ground-training subjects include:\n\n"
        . "- Federal Aviation Regulations.\n"
        . "- Airspace.\n"
        . "- Aerodynamics.\n"
        . "- Aircraft systems.\n"
        . "- Weather.\n"
        . "- Weight and balance.\n"
        . "- Aircraft performance.\n"
        . "- Navigation.\n"
        . "- Airport operations.\n"
        . "- Radio communications.\n"
        . "- Human factors and aeromedical factors.\n"
        . "- Cross-country planning.\n"
        . "- Emergency procedures.\n"
        . "- Private Pilot knowledge-test preparation.\n"
        . "- Private Pilot ACS/checkride preparation.";
}

private function pplAdditionalFeesResponse(): string
{
    return "The published PPL package price is USD 14,950.\n\n"
        . "The program modal says that the following items should be "
        . "identified separately in the enrollment agreement if they "
        . "are not expressly included:\n\n"
        . "- FAA knowledge-test fees.\n"
        . "- Aviation medical examination.\n"
        . "- DPE/checkride fees.\n"
        . "- Other items not expressly included in the enrollment agreement.\n\n"
        . "Please confirm the final fee breakdown with Abbyero Aviation "
        . "before enrollment.";
}

private function pplFullDetailsResponse(): string
{
    return "ABBYERO AVIATION — 30-DAY ACCELERATED PRIVATE PILOT PROGRAM\n\n"
        . "PROGRAM SUMMARY\n"
        . "- Duration: 30 days.\n"
        . "- Total scheduled flight hours: 60.\n"
        . "- Aircraft: Piper PA-28-140 and PA-28R-200.\n"
        . "- Package price: USD 14,950.\n"
        . "- Housing: 30 days included.\n"
        . "- Transportation: Included.\n\n"
        . "FLIGHT ALLOCATION\n"
        . "- PA-28-140: 50 hours (31 dual and 19 solo as published).\n"
        . "- PA-28R-200: 10 hours dual instruction.\n"
        . "- Total: 41 dual and 19 solo hours.\n\n"
        . "ARROW II ENHANCED TRAINING\n"
        . "The 10-hour PA-28R-200 phase introduces complex-aircraft "
        . "operations, including retractable landing gear, constant-speed "
        . "propeller, power management, checklists and emergency procedures. "
        . "This is an enhanced training component, not an FAA PPL requirement.\n\n"
        . "TRAINING\n"
        . "The program includes aircraft control, maneuvers, takeoffs and "
        . "landings, cross-country, navigation, night operations, basic "
        . "instrument flying, emergency procedures, ground instruction "
        . "and FAA Private Pilot ACS/checkride preparation.\n\n"
        . "SCHEDULE\n"
        . "Days 1–5: 10 hours fundamentals and aircraft control.\n"
        . "Days 6–10: 10 hours maneuvers and initial solo.\n"
        . "Days 11–15: 10 hours navigation and cross-country.\n"
        . "Days 16–20: 10 hours night and instrument training.\n"
        . "Days 21–24: 10 hours solo cross-country and ACS proficiency.\n"
        . "Days 25–27: 6 hours Arrow training.\n"
        . "Days 28–29: 4 hours advanced Arrow training.\n"
        . "Day 30: Final review, checkride preparation and weather reserve.\n\n"
        . "ADDITIONAL FEES\n"
        . "FAA knowledge-test, medical examination and DPE/checkride fees "
        . "should be identified separately if not expressly included "
        . "in the enrollment agreement.\n\n"
        . "COMPLETION\n"
        . "Completion within 30 days depends on student proficiency, "
        . "weather, aircraft availability, maintenance and successful "
        . "completion of required FAA milestones.\n\n"
        . "For individual eligibility and final enrollment details, "
        . "please contact Abbyero Aviation staff.";
}
private function pplIncludedResponse(): string
{
    return "The 30-Day Accelerated Private Pilot (PPL) package "
        . "costs USD 14,950 and includes:\n\n"
        . "**Program details:**\n"
        . "- 30-day accelerated training program.\n"
        . "- 60 scheduled flight hours in total.\n"
        . "- 50 hours in the Piper PA-28-140.\n"
        . "- 10 dual hours in the Piper PA-28R-200.\n"
        . "- Planned flight experience of 41 dual hours and 19 solo hours.\n\n"
        . "**Package inclusions:**\n"
        . "- Dual and solo training as applicable.\n"
        . "- 30 days of housing.\n"
        . "- Transportation.\n"
        . "- Ground instruction and flight briefings.\n"
        . "- Night and cross-country training.\n"
        . "- Basic instrument training.\n"
        . "- FAA Private Pilot ACS/checkride preparation.\n\n"
        . "**Additional fees:**\n"
        . "FAA knowledge-test fees, aviation medical examination, "
        . "DPE/checkride fees and other items not expressly included "
        . "in the enrollment agreement are charged separately.\n\n"
        . "Completion within 30 days depends on student proficiency, "
        . "weather, aircraft availability, maintenance and successful "
        . "completion of required FAA milestones.";
}
private function multiEngineScheduleResponse(): string
{
    return "The 15-Day Accelerated Multi-Engine Training schedule is:\n\n"
        . "Days 1–2: Cessna 310J systems, familiarization and normal operations — 1.0–1.5 hours.\n\n"
        . "Days 3–4: Takeoffs, landings and multiengine maneuvering — 1.5–2.0 hours.\n\n"
        . "Days 5–6: Multiengine aerodynamics, VMC and critical engine — 1.5–2.0 hours.\n\n"
        . "Days 7–8: Engine-failure topics, feathering and restart procedures — 1.5–2.0 hours.\n\n"
        . "Days 9–10: Single-engine maneuvering and performance — 1.0–1.5 hours.\n\n"
        . "Days 11–12: Single-engine approaches and emergency operations — 1.0–1.5 hours.\n\n"
        . "Day 13: Advanced emergency scenarios — 1.0–1.5 hours.\n\n"
        . "Day 14: ACS practical-test preparation — 0.5–1.5 hours.\n\n"
        . "Day 15: Mock checkride and final proficiency training — 1.0–1.5 hours.\n\n"
        . "The program provides approximately 10–15 flight hours. "
        . "Actual training time depends on student proficiency, weather, "
        . "maintenance availability and instructor evaluation.";
}

private function multiEngineObjectivesResponse(): string
{
    return "The Multi-Engine Training program uses the Cessna 310J "
        . "and covers these training objectives:\n\n"
        . "- Cessna 310J aircraft familiarization.\n"
        . "- Multiengine aerodynamics.\n"
        . "- Normal and crosswind takeoffs and landings.\n"
        . "- Aircraft systems and limitations.\n"
        . "- Performance and weight-and-balance calculations.\n"
        . "- Critical-engine concepts.\n"
        . "- VMC factors and demonstration.\n"
        . "- Engine-failure recognition and associated training.\n"
        . "- Single-engine aircraft control and performance.\n"
        . "- Single-engine maneuvering, approaches and landings.\n"
        . "- Emergency operations.\n"
        . "- Practical-test preparation.\n\n"
        . "The training is proficiency-based and evaluated by the instructor.";
}

private function multiEngineGroundTrainingResponse(): string
{
    return "Ground training for the Cessna 310J Multi-Engine program includes:\n\n"
        . "1. Cessna 310J aircraft systems.\n"
        . "2. Engines and propeller systems.\n"
        . "3. Fuel, electrical and landing-gear systems.\n"
        . "4. Multiengine aerodynamics.\n"
        . "5. Critical-engine principles.\n"
        . "6. VMC and factors affecting VMC.\n"
        . "7. Single-engine performance.\n"
        . "8. Weight and balance.\n"
        . "9. Takeoff and landing performance.\n"
        . "10. Engine-out procedures.\n"
        . "11. Emergency checklists.\n"
        . "12. Aeronautical decision-making.\n"
        . "13. FAA ACS practical-test preparation.";
}

private function multiEngineFaaResponse(): string
{
    return "The Multi-Engine program references 14 CFR §61.63(c) "
        . "for adding an aircraft class rating to an existing pilot certificate.\n\n"
        . "According to the published program information, the FAA does not "
        . "specify a minimum flight-training-hour requirement for this "
        . "class-rating addition. The applicant must receive the appropriate "
        . "instructor endorsement and pass the applicable practical test.\n\n"
        . "Abbyero's training is proficiency-based and provides approximately "
        . "10–15 flight hours. The training team can confirm the requirements "
        . "applicable to an individual applicant.";
}

private function multiEngineCostBreakdownResponse(): string
{
    return "The published Multi-Engine package breakdown is:\n\n"
        . "10-hour program:\n"
        . "- Flight training: USD 4,100.\n"
        . "- Accommodation: USD 1,050.\n"
        . "- Total: USD 5,150.\n\n"
        . "12-hour program:\n"
        . "- Flight training: USD 4,920.\n"
        . "- Accommodation: USD 1,050.\n"
        . "- Total: USD 5,970.\n\n"
        . "13-hour program:\n"
        . "- Flight training: USD 5,330.\n"
        . "- Accommodation: USD 1,050.\n"
        . "- Total: USD 6,380.\n\n"
        . "14-hour program:\n"
        . "- Flight training: USD 5,740.\n"
        . "- Accommodation: USD 1,050.\n"
        . "- Total: USD 6,790.\n\n"
        . "15-hour program:\n"
        . "- Flight training: USD 6,150.\n"
        . "- Accommodation: USD 1,050.\n"
        . "- Total: USD 7,200.\n\n"
        . "The flight-training rate is USD 410 per hour, and "
        . "15-day accommodation is USD 1,050.";
}

private function multiEngineIncludedResponse(): string
{
    return "The published Abbyero Aviation Multi-Engine package includes:\n\n"
        . "- 10–15 hours of dual instruction.\n"
        . "- A 15-day accelerated training program.\n"
        . "- Cessna 310J aircraft training.\n"
        . "- Accommodation for the program period.\n\n"
        . "The estimated package price ranges from USD 5,150 to USD 7,200, "
        . "depending on the selected flight-hour package.";
}

private function multiEngineExclusionsResponse(): string
{
    return "The Multi-Engine modal identifies these items as not included "
        . "unless specifically incorporated into the package:\n\n"
        . "- FAA/DPE practical-test fees.\n"
        . "- Checkride aircraft time.\n"
        . "- Transportation.\n"
        . "- Meals.\n"
        . "- Flight training beyond 15 hours.\n\n"
        . "Please confirm the final enrollment agreement and any additional "
        . "costs with Abbyero Aviation.";
}

private function multiEngineCompletionResponse(): string
{
    return "The Multi-Engine Training program is proficiency-based rather "
        . "than guaranteeing completion after a fixed number of hours.\n\n"
        . "The published training allocation is approximately 10–15 flight "
        . "hours over 15 days. Actual hours depend on student proficiency, "
        . "weather, maintenance availability and instructor evaluation.\n\n"
        . "The instructor determines the student's readiness for the "
        . "applicable training milestones and practical-test preparation.";
}

private function multiEngineFullDetailsResponse(): string
{
    return "ABBYERO AVIATION — 15-DAY ACCELERATED MULTI-ENGINE TRAINING\n\n"
        . "PROGRAM SUMMARY\n"
        . "- Aircraft: Cessna 310J.\n"
        . "- Duration: 15 days.\n"
        . "- Dual training: approximately 10–15 flight hours.\n"
        . "- Estimated package price: USD 5,150–7,200.\n"
        . "- Accommodation: USD 1,050 for 15 days.\n"
        . "- Flight-training rate: USD 410 per hour.\n\n"
        . "TRAINING OBJECTIVES\n"
        . "Aircraft familiarization, multiengine aerodynamics, aircraft "
        . "systems, performance, weight and balance, critical-engine "
        . "concepts, VMC, engine-failure recognition, single-engine "
        . "performance, emergency operations and practical-test preparation.\n\n"
        . "15-DAY SCHEDULE\n"
        . "Days 1–2: Systems, familiarization and normal operations.\n"
        . "Days 3–4: Takeoffs, landings and multiengine maneuvering.\n"
        . "Days 5–6: Aerodynamics, VMC and critical-engine concepts.\n"
        . "Days 7–8: Engine-failure topics, feathering and restart procedures.\n"
        . "Days 9–10: Single-engine maneuvering and performance.\n"
        . "Days 11–12: Single-engine approaches and emergency operations.\n"
        . "Day 13: Advanced emergency scenarios.\n"
        . "Day 14: ACS practical-test preparation.\n"
        . "Day 15: Mock checkride and final proficiency training.\n\n"
        . "GROUND TRAINING\n"
        . "Cessna 310J systems, engines and propellers, fuel, electrical "
        . "and landing-gear systems, multiengine aerodynamics, critical-engine "
        . "principles, VMC, single-engine performance, weight and balance, "
        . "performance, emergency checklists and aeronautical decision-making.\n\n"
        . "PACKAGE PRICES\n"
        . "10 hours: USD 5,150.\n"
        . "12 hours: USD 5,970.\n"
        . "13 hours: USD 6,380.\n"
        . "14 hours: USD 6,790.\n"
        . "15 hours: USD 7,200.\n\n"
        . "The packages include dual instruction, the 15-day program, "
        . "Cessna 310J training and accommodation.\n\n"
        . "EXCLUSIONS\n"
        . "FAA/DPE practical-test fees, checkride aircraft time, "
        . "transportation, meals and training beyond 15 hours are "
        . "not included unless incorporated into the package.\n\n"
        . "FAA REFERENCE\n"
        . "The program references 14 CFR §61.63(c). The applicable "
        . "instructor endorsement and practical test are required.\n\n"
        . "Training is proficiency-based. Actual hours depend on student "
        . "proficiency, weather, maintenance availability and instructor "
        . "evaluation. Contact Abbyero Aviation to confirm availability "
        . "and the final quotation.";
}
}