<?php

namespace App\Models;

use App\Models\CheckoutRequest;
use App\Models\CheckinRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// $asset->requests
// $asset->isRequestedBy($user)
// $asset->whereRequestedBy($user)
trait Requestable
{

    public function requests()
    {
        return $this->morphMany(CheckoutRequest::class, 'requestable');
    }

    public function isRequestedBy(User $user)
    {
        return $this->requests->where('canceled_at', NULL)->where('user_id', $user->id)->first();
    }

    public function isUnfulfilledRequestedBy(User $user)
    {
        return $this->requests->where('canceled_at', NULL)->where('fulfilled_at', NULL)->where('user_id', $user->id)->first();
    }

    public function scopeRequestedBy($query, User $user)
    {
        return $query->whereHas('requests', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        });
    }

    public function request($qty = 1)
    {
        $this->requests()->save(
            new CheckoutRequest(['user_id' => Auth::id(), 'qty' => $qty])
        );
    }

    public function deleteRequest()
    {
        $this->requests()->where('user_id', Auth::id())->delete();
    }

    public function cancelRequest()
    {
        $this->requests()->where('user_id', Auth::id())->update(['canceled_at' => \Carbon\Carbon::now()]);
    }

    public function isCheckedOutBy(User $user)
    {
        return $this->assigned_to === $user->id;
    }

    public function isReturnRequestedBy(User $user)
    {
        return $this->checkinRequests->where('canceled_at', NULL)->where('user_id', $user->id)->first();
    }

    public function isUnfulfilledReturnRequestedBy(User $user)
    {
        return $this->checkinRequests->where('canceled_at', NULL)->where('fulfilled_at', NULL)->where('user_id', $user->id)->first();
    }

    public function checkinRequests()
    {
        return $this->morphMany(CheckinRequest::class, 'requestable');
    }

    public function requestCheckin($qty = 1)
    {
        $this->checkinRequests()->save(
            new CheckinRequest(['user_id' => Auth::id(), 'qty' => $qty])
        );
    }

    public function deleteReturnRequest()
    {
        $this->checkinRequests()->where('user_id', Auth::id())->delete();
    }

    public function cancelReturnRequest()
    {
        $this->checkinRequests()->where('user_id', Auth::id())->update(['canceled_at' => \Carbon\Carbon::now()]);
    }
}
