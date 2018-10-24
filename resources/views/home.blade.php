@extends ('_layout')

@section('content')
    <table class="table table-bordered">
        <tr>
            <th>Compétence</th>
            @foreach ($persons as $person)
                <th class="rotate">
                    <div><span>{{ $person->name }}</span></div>
                </th>
            @endforeach
        </tr>
        @foreach($competences as $competence)
            <tr>
                <th class="clickable-header" data-cid="{{ $competence->id }}" data-toggle="modal" data-target="#competence{{ $competence->id }}">
                    {{ $competence->shortName }}<br>
                </th>
                @foreach ($persons as $person)
                    <td class="text-center clickable-cell" data-pid="{{ $person->id }}" data-cid="{{ $competence->id }}">{{ isset($totals[$person->id][$competence->id]) ? $totals[$person->id][$competence->id] : "" }}</td>
                @endforeach
            </tr>
        @endforeach
    </table>

    <!-- Generate modals for description -->
    @foreach($competences as $competence)
        <div class="modal hide fade" id="competence{{ $competence->id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ $competence->shortName }}</h5>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-dark">
                            {{ $competence->description }}
                        </div>
                        @if (isset($posevidences[$competence->id]))
                            Elle se manifeste de manière positive par:
                            <ul>
                                @foreach($posevidences[$competence->id] as $evidence)
                                    <li>
                                        {{ $evidence }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        @if (isset($negevidences[$competence->id]))
                            Elle se manifeste de manière négative par:
                            <ul>
                                @foreach($negevidences[$competence->id] as $evidence)
                                    <li>
                                        {{ $evidence }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        @if ($competence->tool)
                            L'outil/méthode proposé est
                            <div>
                                {{ $competence->tool }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('page-specific-js')
    <script type="text/javascript" src="js/home.js"></script>
@endsection