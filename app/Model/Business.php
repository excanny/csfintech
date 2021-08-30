<?php

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    protected $guarded = ['id'];

    // Business types
    public static $RC = 'RC';
    public static $BN = 'BN';
    public static $CAC_IT = 'CAC_IT';

    // Business status
    public static $INITIATED = 'INITIATED';
    public static $VERIFIED = 'VERIFIED';
    public static $ACTIVE = 'ACTIVE';
    public static $INACTIVE = 'INACTIVE';


    public function users () {
        return $this->hasMany(User::class,'business_id');
    }

    public function wallet () {
        return $this->hasOne(Wallet::class, 'business_id');
    }

    public function sage_pay_wallet () {
        return $this->hasOne(SagePayWallet::class, 'business_id');
    }

    public function transactions(){

        return $this->hasMany(Transaction::class);
    }

    public function wallet_transactions(){

        return $this->hasMany(WalletTransaction::class);
    }

    public function fee ()
    {
        return $this->hasOne( Fee::class);
    }

    public function getProduct( $productName )
    {
        $products = $this->fee->products;
        $index = array_search($productName, array_column($products, 'name'));
        if ($index !== false) {
            return $products[$index];
        }

        return null;
    }

    public function topUpRequests () {
        return $this->hasMany(WalletTopUpRequest::class);
    }

    public function disputes () {
        return $this->hasMany(Dispute::class);
    }

    public function commissions () {
        return $this->hasMany(Commission::class);
    }

    public function commissionTransactions()
    {
        return $this->hasMany(commissionTransaction::class);
    }

    public function directors () {
        return $this->hasMany(Director::class);
    }

    public function beneficial_owners () {
        return $this->hasMany(BeneficialOwner::class);
    }

    public function sage_pay_transactions (){

        return $this->hasMany(SagePayTransaction::class);
    }

    public function sage_pay_settings () {
        return $this->hasOne(SagePaySetting::class);
    }

}
