<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App;

class HomeController extends Controller
{
    //
    public function Index()
    {
        $persons = App\Person::orderBy('name')->get();
        $competences = App\Competence::orderBy('importance','desc')->get();
        $posevidences = array();
        $negevidences = array();
        foreach (\DB::table('evidences')->get() as $evidence)
            if ($evidence->basicWeight > 0)
                $posevidences[$evidence->competence_id][] = $evidence->description;
            else
                $negevidences[$evidence->competence_id][] = $evidence->description;

        $observations = \DB::table('observations')
            ->join('evidences', 'evidences.id', '=', 'evidence_id')
            ->selectRaw('person_id as pid, competence_id as cid, sum(weight) as total, count(weight) as nb')
            ->groupBy('pid')
            ->groupBy('cid')
            ->get();

        $totals = array();
        foreach ($observations as $ob)
            $totals[$ob->pid][$ob->cid] = ['total' => $ob->total, 'nb' => $ob->nb];

        return view('home')
            ->with('persons', $persons)
            ->with('competences', $competences)
            ->with('totals', $totals)
            ->with('posevidences', $posevidences)
            ->with('negevidences', $negevidences);
    }

    public function summary()
    {
        $competences = App\Competence::orderBy('importance','desc')->get();
        $posevidences = array();
        $negevidences = array();
        foreach (\DB::table('evidences')->get() as $evidence)
            if ($evidence->basicWeight > 0)
                $posevidences[$evidence->competence_id][] = $evidence->description;
            else
                $negevidences[$evidence->competence_id][] = $evidence->description;

        return view('summary')
            ->with('competences', $competences)
            ->with('posevidences', $posevidences)
            ->with('negevidences', $negevidences);
    }

    public function observations($pid, $cid) // person id, competence id
    {
        $observations = \DB::table('observations')
            ->join('evidences', 'evidences.id', '=', 'evidence_id')
            ->where('person_id', '=', $pid)
            ->where('competence_id', '=', $cid)
            ->select('timestamp', 'details', 'weight', 'description')
            ->orderBy('timestamp','asc')
            ->get();
        $person = \DB::table('persons')
            ->where('persons.id', '=', $pid)
            ->select('id', 'name')
            ->first();
        $competence = \DB::table('competences')
            ->where('competences.id', '=', $cid)
            ->select('id', 'shortName', 'description', 'tool')
            ->first();
        $posevidences = \DB::table('evidences')
            ->where('competence_id', '=', $cid)
            ->where('basicWeight', '>', 0)
            ->select('id','description','basicWeight')
            ->get();
        $negevidences = \DB::table('evidences')
            ->where('competence_id', '=', $cid)
            ->where('basicWeight', '<', 0)
            ->select('id','description','basicWeight')
            ->get();

        return view('observations')
            ->with('observations', $observations)
            ->with('person', $person)
            ->with('competence', $competence)
            ->with('posevidences', $posevidences)
            ->with('negevidences', $negevidences);
    }

    public function newobservation(Request $request)
    {
        $details = $request->details;
        $weight = $request->weight;
        $evidence = $request->evidence;
        $newevdesc = $request->newevdesc;
        $cid = $request->cid;
        $pid = $request->pid;

        if ($weight != 0 && strlen($details) > 5) // probably ok to add
        {
            if ($evidence == -1 && strlen($newevdesc) > 5) // new evidence
            {
                $newev = new App\Evidence();
                $newev->description = $newevdesc;
                $newev->basicWeight = $weight;
                $newev->competence_id = $cid;
                $newev->save();
                $evidence = $newev->id;
            }
            if ($evidence > 0) // ok to add
            {
                $nobs = new App\Observation();
                $nobs->person_id = $pid;
                $nobs->evidence_id = $evidence;
                $nobs->weight = $weight;
                $nobs->details = $details;
                $nobs->save();
            }
        }
        return $this->observations($request->pid, $request->cid);
    }

    public function newevidence(Request $request)
    {
        $bweight = $request->bweight;
        $cid = $request->cid;
        $description = $request->description;

        if ($bweight != 0 && strlen($description) > 5 && $cid > 0) // ok to add
        {
                $newev = new App\Evidence();
                $newev->description = $description;
                $newev->basicWeight = $bweight;
                $newev->competence_id = $cid;
                $newev->save();
        }
        return $this->summary();
    }
}
