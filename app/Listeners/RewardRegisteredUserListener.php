<?php

namespace Gentcmen\Listeners;

use Gentcmen\Events\UserReferredEvent;
use Gentcmen\Models\ReferralLink;
use Gentcmen\Models\ReferralProgramUserStep;
use Gentcmen\Models\ReferralRelationship;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RewardRegisteredUserListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserReferredEvent  $event
     * @return void
     */
    public function handle(UserReferredEvent $event)
    {
        $referral = ReferralLink::find($event->referralId);

        if (!is_null($referral)) {
            ReferralRelationship::create([
                'referral_link_id' => $referral->id,
                'user_id' => $event->user->id
            ]);
            // @note:
            // User who was sharing link
            // $provider = $referral->user;
            // User who used the link
            // $user = $event->user;
            if ($referral->program->name === 'Invite friends') {
                $provider = $referral->user;
                $amountInvitedUsers = count($referral->relationships);
                $counter = 0; // counter to count program steps that are completed

                foreach ($referral->program->programSteps as $programStep) {
                    $referralProgramStep = ReferralProgramUserStep::firstOrCreate(
                            [
                                'program_id' => $referral->program->id,
                                'step_id' => $programStep->id,
                                'user_id' => $provider->id,
                            ],
                            [
                                'program_id' => $referral->program->id,
                                'step_id' => $programStep->id,
                                'user_id' => $provider->id,
                                'completed' => 0
                            ]
                        );

                    if ($amountInvitedUsers === $programStep->goal) {
                        $provider->incrementBonusPoints($programStep->reward);
                        // mark record as completed step
                        $referralProgramStep->update([
                            'completed' => 1
                        ]);
                    }

                    if ($referralProgramStep->completed) {
                        $counter++;
                    }
                }

                $programSteps = ReferralProgramUserStep::where([
                    ['program_id', $referral->program->id],
                    ['user_id', $provider->id]
                ])->get();

                // if user has completed all steps, award him the program bonus
                if ($counter === count($programSteps)) {
                    $provider->incrementBonusPoints($referral->program->reward);
                }
            }
        }
    }
}
