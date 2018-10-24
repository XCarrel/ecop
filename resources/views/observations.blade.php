@extends ('_layout')

@section('content')

    <h1>Observations de {{ $competence->shortName }} pour {{ $person->name }}</h1>
    @if (count($observations) > 0)
        <h3>
            Il y en a {{ count($observations) }}
        </h3>
        <ul>
            @foreach ($observations as $observation)
                <li>
                    Le {{ Carbon\Carbon::parse($observation->timestamp)->format('d M Y à H:i') }}, {{ $observation->weight > 0 ? "+".$observation->weight : $observation->weight }} parce que: {{ $observation->details }}
                </li>
            @endforeach
        </ul>
    @else
        <h3>
            Il n'y en a pas
        </h3>
    @endif

    <h3>Nouvelle observation</h3>
    <form action="/newobservation" method="post">
        @csrf
        <div class="row align-top">
            <label class="col col-1 text-right" for="iddetail">Détails:</label>
            <textarea id="details" name="details" cols="50" class="col form-control col-6"></textarea>
        </div>
        <div class="row">
            <label class="col col-1 text-right" for="idweight">Poids:</label>
            <input type="number" id="weight" name="weight" class="col col-1 form-control" value="1">
        </div>
        <div class="row">
            <label class="col col-1 text-right">Lié à:</label>
            <fieldset class="col col-6">
                @foreach($posevidences as $posevidence)
                    <input type="radio" name="evidence" value="{{ $posevidence->id }}" data-weight="{{ $posevidence->basicWeight }}" class="mr-2"> {{ $posevidence->description }}<br>
                @endforeach
                @foreach($negevidences as $negevidence)
                    <input type="radio" name="evidence" value="{{ $negevidence->id }}" data-weight="{{ $negevidence->basicWeight }}" class="mr-2"> {{ $negevidence->description }}<br>
                @endforeach
                <input type="radio" id="otherev" name="evidence" value="-1" class="mr-2">Autre: <textarea id="newevdesc" name="newevdesc" cols="80" class="col form-control"></textarea><br>
            </fieldset>
        </div>
        <div class="row">
            <div class="col col-1">
                <input type="hidden" name="cid" value="{{ $competence->id }}">
                <input type="hidden" name="pid" value="{{ $person->id }}">
            </div>
                <span class="mr-5"><button id="btnsubmit" type="submit" class="form-control btn-success">Enregistrer</button></span>
                <a class="btn btn-info" href="/">Annuler</a>
            </div>
        </div>
    </form>

@endsection

@section('page-specific-js')
    <script type="text/javascript" src="/js/observations.js"></script>
@endsection