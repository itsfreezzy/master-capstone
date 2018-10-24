<?php

namespace App\Console;

use App\UserLog;
use App\Reservation;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $reservations = Reservation::all();
        $admins = User::select('email')->get();

        foreach ($reservations as $reservation) {
            $customer = Customer::where('code', $reservation->customercode)->first();  
            $date = strtotime(date('Y-m-d h:i:s', strtotime($reservation->created_at)));
            $date = strtotime('+7 days', $date);

            if ($reservation->status == 'Pending') {
                $days = 8 - date_diff(date_create($reservation->created_at), date_create(date('Y-m-d h:i:s')))->days;
                
                if (date_diff(date_create($reservation->created_at), date_create(date('Y-m-d h:i:s')))->days <= 0) {
                    $reservation->status = 'Cancelled';
                    $reservation->cancelGrounds = 'Non-payment of reservation fee';
                    $reservation->delete();

                    \Mail::to($customer->email)->cc($admins->toArray())->send(new NonPaymentOfReservation($reservation, $customer));
                } else if (date_diff(date_create($reservation->created_at), date_create(date('Y-m-d h:i:s')))->days <= 7 && date_diff(date_create($reservation->created_at), date_create(date('Y-m-d h:i:s')))->days > 0){ 
                    \Mail::to($customer->email)->cc($admins->toArray())->send(new PaymentReminder($reservation, 'Reservation Fee', $days, $customer));
                }
            } else if ($reservation->status == 'Confirmed') {
                $dp = true;
                $fp = true;
                $sd = true;
                $deadline = strtotime(date('Y-m-d h:i:s', strtotime($reservation->created_at)));
                $deadline = strtotime('+30 days', $deadline);
                $deadline = date('Y-m-d h:i:s', $deadline);

                if (count( Payment::where('reservationcode', $reservation->code)->where('paymenttype', '50% Downpayment')->get() ) == 0) {
                    $dp = false;

                    if (date_diff(date_create($reservation->created_at), date_create(date('Y-m-d h:i:s')))->days > 30 && date_diff(date_create($deadline), date_create(date('Y-m-d h:i:s')))->invert == 0) {
                        $reservation->status = 'Cancelled';
                        $reservation->cancelGrounds = 'Non-payment of 50% Downpayment';
                        $reservation->delete();
                        
                        \Mail::to($customer->email)->cc($admins->toArray())->send(new NonPaymentOfDP($reservation, $customer));
                    } else if (date_diff(date_create($reservation->created_at), date_create(date('Y-m-d h:i:s')))->days <= 30 && date_diff(date_create($deadline), date_create(date('Y-m-d h:i:s')))->days > 23) {
                        $days = 30 - date_diff(date_create($reservation->created_at), date_create(date('Y-m-d h:i:s')))->days;
                        \Mail::to($customer->email)->cc($admins->toArray())->send(new PaymentReminder($reservation, '50% Down Payment', $days, $customer));
                    }
                }

                if ($dp && count( Payment::where('reservationcode', $reservation->code)->where('paymenttype', '50% Full Payment')->get() ) == 0) {
                    $deadline = strtotime(date('Y-m-d h:i:s', strtotime($reservation->eventdate)));
                    $deadline = strtotime('-30 days', $deadline);
                    $deadline = date('Y-m-d h:i:s', $deadline);
                    $fp = false;

                    if (date_diff(date_create($deadline), date_create(date('Y-m-d h:i:s')))->days <= 7 && date_diff(date_create($deadline), date_create(date('Y-m-d h:i:s')))->days >= 0 && date_diff(date_create($deadline), date_create(date('Y-m-d h:i:s')))->invert == 1) {
                        $days = date_diff(date_create($deadline), date_create(date('Y-m-d h:i:s')))->days;
                        \Mail::to($customer->email)->cc($admins->toArray())->send(new PaymentReminder($reservation, '50% Full Payment', $days, $customer));
                    }
                }

                if ($dp && $fp && count( Payment::where('reservationcode', $reservation->code)->where('paymenttype', 'Security Deposit')->get() ) == 0) {
                    $deadline = strtotime(date('Y-m-d h:i:s', strtotime($reservation->eventdate)));
                    $deadline = strtotime('-15 days', $deadline);
                    $deadline = date('Y-m-d h:i:s', $deadline);
                    $sd = false;

                    if (date_diff(date_create($deadline), date_create(date('Y-m-d h:i:s')))->days <= 7 && date_diff(date_create($deadline), date_create(date('Y-m-d h:i:s')))->days >= 0 && date_diff(date_create($deadline), date_create(date('Y-m-d h:i:s')))->invert == 1) {
                        $days = date_diff(date_create($deadline), date_create(date('Y-m-d h:i:s')))->days;
                        \Mail::to($customer->email)->cc($admins->toArray())->send(new PaymentReminder($reservation, 'Security Deposit', $days, $customer));
                    }
                }
            }
        }
        });
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
