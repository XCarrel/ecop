@extends ('_layout')

@section('content')
    <table class="table table-bordered">
        <tr>
            <th>Compétence</th>
            <th>Signe positif</th>
            <th>Signe négatif</th>
        </tr>
        @foreach($competences as $competence)
            <tr>
                <td>
                    <h3>{{ $competence->shortName }}</h3>
                    {{ $competence->description }}
                </td>
                <td>
                    <ul>
                        @foreach($posevidences[$competence->id] as $evidence)
                            <li>
                                {{ $evidence }}
                            </li>
                        @endforeach
                    </ul>
                </td>
                <td>
                    <ul>
                        @foreach($negevidences[$competence->id] as $evidence)
                            <li>
                                {{ $evidence }}
                            </li>
                        @endforeach
                    </ul>
                </td>
            </tr>
        @endforeach
    </table>

    <h4>Ajoute une observation</h4>
    <form method="post" action="/newevidence">
        @csrf()
        <select name="cid" class="form-control col-3">
            <option value="0" selected>--- Choisis une compétence ---</option>
            @foreach ($competences as $competence)
                <option value="{{ $competence->id }}">{{ $competence->shortName }}</option>
            @endforeach
        </select>
        <label for="bweight">Poids de base</label>
        <input type="number" id="bweight" name="bweight"><br>
        <textarea name="description" class="form-control col-6"></textarea><br>
        <button class="form-control btn btn-info col-1" type="submit">Ok</button>
    </form>

    <a href="/" class="btn btn-link">Retour</a>

@endsection
