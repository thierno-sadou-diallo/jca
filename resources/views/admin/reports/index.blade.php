<x-admin.layout title="Statistiques">
    <section class="admin-stats report-stats">
        @foreach ($kpis as $label => $value)
            <article><span>{{ ucfirst($label) }}</span><strong>{{ $value }}</strong></article>
        @endforeach
    </section>

    <section class="admin-grid">
        <article class="admin-panel">
            <div class="admin-panel-head">
                <h2>Demandes par mois</h2>
                <span>6 derniers mois</span>
            </div>
            <div class="report-bars">
                @foreach ($monthlyLeads as $item)
                    <div>
                        <span>{{ $item['label'] }}</span>
                        <strong style="--bar: {{ max(6, min(100, $item['value'] * 16)) }}%">{{ $item['value'] }}</strong>
                    </div>
                @endforeach
            </div>
        </article>

        <article class="admin-panel">
            <div class="admin-panel-head">
                <h2>Types de clients</h2>
                <span>Profils</span>
            </div>
            <div class="report-bars">
                @forelse ($clientTypes as $item)
                    <div>
                        <span>{{ $item->type_client ?: 'Non indique' }}</span>
                        <strong style="--bar: {{ max(8, min(100, $item->total * 12)) }}%">{{ $item->total }}</strong>
                    </div>
                @empty
                    <div><span>Aucun client</span><strong style="--bar: 8%">0</strong></div>
                @endforelse
            </div>
        </article>
    </section>

    <section class="module-grid report-modules">
        @foreach ([
            'Demandes' => $leadStatuses,
            'Documents' => $documentStatuses,
            'Dossiers' => $caseStatuses,
            'Rendez-vous' => $appointmentStatuses,
            'Candidatures' => $applicationStatuses,
        ] as $title => $items)
            <article class="admin-panel">
                <div class="admin-panel-head">
                    <h2>{{ $title }}</h2>
                    <span>Par statut</span>
                </div>
                <div class="report-bars compact-report-bars">
                    @forelse ($items as $item)
                        <div>
                            <span>{{ $item['label'] }}</span>
                            <strong style="--bar: {{ max(8, min(100, $item['value'] * 12)) }}%">{{ $item['value'] }}</strong>
                        </div>
                    @empty
                        <div><span>Aucune donnee</span><strong style="--bar: 8%">0</strong></div>
                    @endforelse
                </div>
            </article>
        @endforeach
    </section>
</x-admin.layout>
