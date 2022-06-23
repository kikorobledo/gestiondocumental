<?php

namespace App\Observers;

use App\Models\Entrie;
use App\Models\Tracking;
use App\Models\Conclusion;
use Illuminate\Support\Facades\Storage;

class EntrieObserver
{
    /**
     * Handle the Entrie "created" event.
     *
     * @param  \App\Models\Entrie  $entrie
     * @return void
     */
    public function created(Entrie $entrie)
    {
        //
    }

    /**
     * Handle the Entrie "updated" event.
     *
     * @param  \App\Models\Entrie  $entrie
     * @return void
     */
    public function updated(Entrie $entrie)
    {
        //
    }

    /**
     * Handle the Entrie "deleted" event.
     *
     * @param  \App\Models\Entrie  $entrie
     * @return void
     */
    public function deleting(Entrie $entrie)
    {

        $trackings = Tracking::with('files')->where('entrie_id', $entrie->id)->get();

        if($trackings->count()){

            foreach ($trackings as $tracking) {

                foreach($tracking->files as $file){

                    Storage::disk('pdfs')->delete($file->url);

                    $file->delete();

                }

            }

        }

        $conclusions = Conclusion::with('files')->where('entrie_id', $entrie->id)->get();

        if($conclusions->count()){

            foreach ($conclusions as $conclusion) {

                foreach($conclusion->files as $file){

                    Storage::disk('pdfs')->delete($file->url);

                    $file->delete();

                }

            }

        }

        if($entrie->files()->count()){

            foreach ($entrie->files as $file) {

                Storage::disk('pdfs')->delete($file->url);

                $file->delete();
            }

        }

    }

    /**
     * Handle the Entrie "restored" event.
     *
     * @param  \App\Models\Entrie  $entrie
     * @return void
     */
    public function restored(Entrie $entrie)
    {
        //
    }

    /**
     * Handle the Entrie "force deleted" event.
     *
     * @param  \App\Models\Entrie  $entrie
     * @return void
     */
    public function forceDeleted(Entrie $entrie)
    {
        //
    }
}
