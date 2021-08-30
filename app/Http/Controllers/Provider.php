<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Provider extends Controller
{
    /**
     * @var \App\Model\Provider
     */
    private $provider;

    public function __construct(\App\Model\Provider $provider )
    {
        $this->provider = $provider;
    }

    public function index() {
//        $providers = \App\Model\Provider::orderBy('id', 'desc')->get();

        //Toggle for every product
        $airtime_providers = \App\Model\Provider::where('product','AIRTIME')->orderBy('id', 'desc')->get();
        $data_providers = \App\Model\Provider::where('product','DATA')->orderBy('id', 'desc')->get();
        $electricity_providers = \App\Model\Provider::where('product','ELECTRICITY')->orderBy('id', 'desc')->get();
        $cableTv_providers = \App\Model\Provider::where('product','CABLETV')->orderBy('id', 'desc')->get();

        return view('admin.settings.toggleProviders', compact('airtime_providers',
            'data_providers','electricity_providers','cableTv_providers'));
    }

    public function switchProvider (Request $request) {


        $data = $request->all();


        if (!$request->has('type')){
            return back()->with('error', 'Please select a product');
        }


        if($request->get('type') == 'airtime'){


            $provider_id = $data['airtime_provider'];


            //Select All AIRTIME Providers
            $providers = $this->provider->whereProduct('AIRTIME')->orderBy('id', 'desc')->get();

            $new_provider = $this->provider->find($provider_id);

            if ($new_provider->status == \App\Model\Provider::$INACTIVE){

                if ($new_provider) {

                    //Make all providers inactive
                    foreach ($providers as $item) {
                        $item->update([
                            'status' => \App\Model\Provider::$INACTIVE
                        ]);
                    }

                }



                //Update selected provider as active
                $new_provider->update([
                    'status' => \App\Model\Provider::$ACTIVE
                ]);

            }

        }

        if($request->get('type') == 'data'){

            $provider_id = $data['data_provider'];

            $providers = $this->provider->whereProduct('DATA')->orderBy('id', 'desc')->get();

            $new_provider = $this->provider->find($provider_id);

            if ($new_provider->status == \App\Model\Provider::$INACTIVE) {

                if ($new_provider) {
                    foreach ($providers as $item) {
                        $item->update([
                            'status' => \App\Model\Provider::$INACTIVE
                        ]);
                    }
                }

                $new_provider->update([
                    'status' => \App\Model\Provider::$ACTIVE
                ]);

            }
        }

        if($request->get('type') == 'cabletv'){

            $provider_id = $data['cableTv_provider'];

            $providers = $this->provider->whereProduct('CABLETV')->orderBy('id', 'desc')->get();

            $new_provider = $this->provider->find($provider_id);

            if ($new_provider->status == \App\Model\Provider::$INACTIVE) {

                if ($new_provider) {
                    foreach ($providers as $item) {
                        $item->update([
                            'status' => \App\Model\Provider::$INACTIVE
                        ]);
                    }
                }

                $new_provider->update([
                    'status' => \App\Model\Provider::$ACTIVE
                ]);

            }
        }

        if($request->get('type') == 'electricity'){

            $provider_id = $data['electricity_provider'];

            $providers = $this->provider->whereProduct('ELECTRICITY')->orderBy('id', 'desc')->get();

            $new_provider = $this->provider->find($provider_id);

            if ($new_provider->status == \App\Model\Provider::$INACTIVE) {

                if ($new_provider) {
                    foreach ($providers as $item) {
                        $item->update([
                            'status' => \App\Model\Provider::$INACTIVE
                        ]);
                    }
                }

                $new_provider->update([
                    'status' => \App\Model\Provider::$ACTIVE
                ]);

            }
        }


        return back()->with('success', 'Switched Provider Successfully');
    }

}
