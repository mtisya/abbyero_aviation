<div class="modal fade" id="performMaintenanceModal" tabindex="-1" aria-hidden="true" style="display:none;">

    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">

        <form method="POST" action="{{ route('maintenance.perform') }}">

            @csrf

            <input type="hidden" name="schedule_id" id="schedule_id">

            <div class="modal-content">

                {{-- Header --}}
                <div class="modal-header text-white" style="background: linear-gradient(#4886a3);">

                    <h5 class="modal-title">

                        <i class="bi bi-tools"></i>

                        Perform Aircraft Maintenance

                    </h5>

                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    {{-- Aircraft Information --}}
                    <div class="card shadow-sm mb-4">

                        <div class="card-header bg-light">

                            <strong>Aircraft Information</strong>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-4">

                                    <label class="fw-bold">
                                        Registration
                                    </label>

                                    <div id="modal_aircraft" class="form-control bg-light"></div>

                                </div>

                                <div class="col-md-4">

                                    <label class="fw-bold">
                                        Aircraft Model
                                    </label>

                                    <div id="modal_model" class="form-control bg-light"></div>

                                </div>

                                <div class="col-md-4">

                                    <label class="fw-bold">
                                        Maintenance Type
                                    </label>

                                    <div id="modal_type" class="form-control bg-light"></div>

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- Current Status --}}
                    <div class="card shadow-sm mb-4">

                        <div class="card-header bg-light">

                            <strong>Current Aircraft Status</strong>

                        </div>

                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-3">
                                    <label>Performed Tach</label>
                                    <input id="current_tach" name="performed_tach" class="form-control" readonly>
                                </div>

                                <div class="col-md-3">
                                    <label>Performed Hobbs</label>
                                    <input id="current_hobbs" name="performed_hobbs" class="form-control" readonly>
                                </div>

                                <div class="col-md-3">

                                    <label>Next Due Tach</label>

                                    <input id="next_due_tach" class="form-control" readonly>

                                </div>

                                <div class="col-md-3">

                                    <label>Next Due Date</label>

                                    <input id="next_due_date" class="form-control" readonly>

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- Maintenance Record --}}
                    <div class="card shadow-sm">

                        <div class="card-header bg-light">

                            <strong>Maintenance Record</strong>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-4 mb-3">

                                    <label>Performed Date</label>

                                    <input type="date" name="performed_date" class="form-control"
                                        value="{{ now()->toDateString() }}">

                                </div>

                                <div class="col-md-4 mb-3">

                                    <label>Engineer</label>

                                    <input type="text" name="performed_by" class="form-control">

                                </div>

                                <div class="col-md-4 mb-3">

                                    <label>Engineer License</label>

                                    <input type="text" name="engineer_license" class="form-control">

                                </div>

                                <div class="col-md-4 mb-3">

                                    <label>Maintenance Cost</label>

                                    <input type="number" step="0.01" name="cost" class="form-control">

                                </div>

                                <div class="col-md-8 mb-3">

                                    <label>Parts Replaced</label>

                                    <input type="text" name="parts_replaced" class="form-control">

                                </div>

                                <div class="col-md-12 mb-3">

                                    <label>Work Performed</label>

                                    <textarea name="work_performed" rows="4" class="form-control"></textarea>

                                </div>

                                <div class="col-md-12">

                                    <label>Remarks</label>

                                    <textarea name="remarks" rows="3" class="form-control"></textarea>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button class="btn btn-success">

                        <i class="bi bi-check-circle"></i>

                        Complete Maintenance

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

<script>
    async function loadMaintenance(id) {
        // reset modal first (VERY IMPORTANT)
        document.getElementById('schedule_id').value = '';
        document.getElementById('modal_aircraft').innerText = 'Loading...';
        document.getElementById('modal_model').innerText = '';
        document.getElementById('modal_type').innerText = '';
        document.getElementById('current_tach').value = '';
        document.getElementById('current_hobbs').value = '';
        document.getElementById('next_due_tach').value = '-';
        document.getElementById('next_due_date').value = '-';

        const res = await fetch(`/maintenance/${id}/details`);
        const data = await res.json();

        // schedule id
        document.getElementById('schedule_id').value = data.schedule.id;

        // aircraft
        document.getElementById('modal_aircraft').innerText =
            data.aircraft.registration;

        document.getElementById('modal_model').innerText =
            data.aircraft.model;

        document.getElementById('modal_type').innerText =
            data.schedule.maintenance_type;

        // current status
        document.getElementById('current_tach').value =
            data.current_tach ?? 0;

        document.getElementById('current_hobbs').value =
            data.current_hobbs ?? 0;

        document.getElementById('next_due_tach').value =
            data.schedule.next_due_tach ?? 'Date Based';

        document.getElementById('next_due_date').value =
            data.schedule.next_due_date ?? 'Tach Based';
    }
</script>