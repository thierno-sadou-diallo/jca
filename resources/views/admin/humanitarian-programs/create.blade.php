<x-admin.layout title="Nouveau programme humanitaire">
    <section class="admin-panel admin-form-panel">
        <div class="admin-panel-head">
            <h2>Creer un programme humanitaire</h2>
            <span>Piloter une initiative sociale ou solidaire</span>
        </div>
        <form class="lead-form admin-form" method="post" action="{{ route('admin.humanitarian-programs.store') }}" enctype="multipart/form-data">
            @include('admin.humanitarian-programs._form', ['submitLabel' => 'Creer'])
        </form>
    </section>
</x-admin.layout>
