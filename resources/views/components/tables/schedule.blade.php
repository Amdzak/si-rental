<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6>Schedule</h6>
                <div class="d-flex">
                    <input type="text" id="searchInput" class="form-control form-control-sm me-2"
                        placeholder="Search schedules..." onkeyup="searchSchedules()"
                        style="height: 2.1rem; padding: 5px;">
                    <a href="javascript:;" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#scheduleModal" onclick="openAddModal()">Add</a>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Machine
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Operator</th>
                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Date</th>
                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Damage Type</th>
                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Time Type</th>
                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Status</th>
                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Action</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data akan diisi di sini -->
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <button class="btn btn-primary btn-sm ms-3 py-2 px-3" onclick="prevPage()">Prev</button>
                    <button class="btn btn-primary btn-sm me-3 py-2 px-3" onclick="nextPage()">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Adding and Editing Schedule -->
<div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scheduleModalLabel">Add/Edit/View Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="scheduleForm">
                    <input type="hidden" id="schedule_id">
                    <div class="mb-3">
                        <label for="machine_id" class="form-label">Machine</label>
                        <input type="text" class="form-control" id="machine_id" required>
                    </div>
                    <div class="mb-3">
                        <label for="operator_id" class="form-label">Operator</label>
                        <input type="text" class="form-control" id="operator_id" required>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" required>
                    </div>
                    <div class="mb-3">
                        <label for="damage_type" class="form-label">Damage Type</label>
                        <select class="form-control" id="damage_type" required>
                            <option value="ringan">Ringan</option>
                            <option value="sedang">Sedang</option>
                            <option value="berat">Berat</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="time_type" class="form-label">Time Type</label>
                        <select class="form-control" id="time_type" required>
                            <option value="harian">Harian</option>
                            <option value="mingguan">Mingguan</option>
                            <option value="bulanan">Bulanan</option>
                            <option value="tahunan">Tahunan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" required>
                            <option value="dijadwalkan">Dijadwalkan</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                </form>
                <div id="scheduleDetails" style="display: none;">
                    <h6>Schedule Details</h6>
                    <p id="detailMachineName"></p>
                    <p id="detailOperator"></p>
                    <p id="detailDate"></p>
                    <p id="detailDamageType"></p>
                    <p id="detailTimeType"></p>
                    <p id="detailStatus"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveScheduleButton" onclick="saveSchedule()">Save
                    changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan jQuery di dalam head atau sebelum script lainnya -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    let currentPage = 1;
    const recordsPerPage = 10;
    let schedules = [];
    let originalSchedules = [];
    document.addEventListener('DOMContentLoaded', function() {
        fetchSchedules();
    });

    function fetchSchedules() {
        axios.get('/api/schedule')
            .then(response => {
                schedules = response.data.data; // Save all schedules in the array
                originalSchedules = [...schedules];
                displaySchedules(); // Display schedules for the current page
            })
            .catch(error => {
                console.error('Error fetching schedules:', error);
            });
    }

    function displaySchedules() {
        const tbody = document.querySelector('tbody');
        tbody.innerHTML = '';

        // Calculate start and end indices for current page
        const start = (currentPage - 1) * recordsPerPage;
        const end = start + recordsPerPage;

        const paginatedSchedules = schedules.slice(start, end); // Get schedules for the current page

        paginatedSchedules.forEach(schedule => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="align-middle text-center text-sm">${schedule.id_machine}</td>
                <td class="align-middle text-center text-sm">${schedule.id_operator}</td>
                <td class="align-middle text-center text-sm">${new Date(schedule.date).toLocaleDateString('en-GB')}</td>
                <td class="align-middle text-center">${schedule.damage_type}</td>
                <td class="align-middle text-center">${schedule.time_type}</td>
                <td class="align-middle text-center">${schedule.status}</td>
                <td class="align-middle text-center">
                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs"
                        onclick="openEditModal('${schedule.id_schedule}')">Edit</a>
                    &nbsp;|&nbsp;
                    <a href="javascript:;" class="text-danger font-weight-bold text-xs"
                        onclick="confirmDelete('${schedule.id_schedule}')">Delete</a>
                    &nbsp;|&nbsp;
                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs"
                        onclick="viewDetails('${schedule.id_schedule}')">View</a>
                </td>
            `;
            tbody.appendChild(row);
        });

        // Update pagination buttons
        updatePagination();
    }

    function updatePagination() {
        const pageCount = Math.ceil(schedules.length / recordsPerPage);
        const prevButton = document.querySelector('.btn-primary[onclick="prevPage()"]');
        const nextButton = document.querySelector('.btn-primary[onclick="nextPage()"]');

        prevButton.disabled = currentPage === 1; // Disable prev button if on the first page
        nextButton.disabled = currentPage === pageCount || pageCount === 0; // Disable next button if on the last page
    }

    function nextPage() {
        const pageCount = Math.ceil(schedules.length / recordsPerPage);
        if (currentPage < pageCount) {
            currentPage++;
            displaySchedules();
        }
    }

    function prevPage() {
        if (currentPage > 1) {
            currentPage--;
            displaySchedules();
        }
    }

    function searchSchedules() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        currentPage = 1; // Reset to the first page on search

        if (query === '') {
            schedules = [...originalSchedules]; // Restore the original schedules if the query is empty
        } else {
            schedules = originalSchedules.filter(schedule =>
                schedule.id_machine.toLowerCase().includes(query) ||
                schedule.id_operator.toLowerCase().includes(query) ||
                new Date(schedule.date).toLocaleDateString('en-GB').includes(query) ||
                schedule.damage_type.toLowerCase().includes(query) ||
                schedule.time_type.toLowerCase().includes(query) ||
                schedule.status.toLowerCase().includes(query)
            );
            displaySchedules();
        }
    }

    function openAddModal() {
        document.getElementById('scheduleForm').reset();
        document.getElementById('schedule_id').value = '';
        document.getElementById('scheduleModalLabel').innerText = 'Add Schedule';
        document.getElementById('saveScheduleButton').style.display = 'inline-block';
        document.getElementById('scheduleForm').style.display = 'block';
        document.getElementById('scheduleDetails').style.display = 'none';
    }

    function closeModal() {
        const modal = new bootstrap.Modal(document.getElementById('scheduleModal'));
        modal.hide(); // Menutup modal
    }

    function openEditModal(scheduleId) {
        // Mengambil data jadwal dari MySQL
        axios.get(`/api/schedule/${scheduleId}`)
            .then(response => {
                const schedule = response.data.data;
                if (!schedule) {
                    console.error('Schedule not found:', scheduleId);
                    return;
                }

                // Mengisi form dengan data yang diambil
                document.getElementById('schedule_id').value = schedule.id_schedule;
                document.getElementById('machine_id').value = schedule.id_machine;
                document.getElementById('operator_id').value = schedule.id_operator;
                document.getElementById('date').value = new Date(schedule.date).toISOString().split('T')[0];
                document.getElementById('damage_type').value = schedule.damage_type;
                document.getElementById('time_type').value = schedule.time_type;
                document.getElementById('status').value = schedule.status;

                document.getElementById('scheduleModalLabel').innerText = 'Edit Schedule';
                document.getElementById('saveScheduleButton').style.display = 'inline-block';
                document.getElementById('scheduleForm').style.display = 'block';
                document.getElementById('scheduleDetails').style.display = 'none';

                const scheduleModal = new bootstrap.Modal(document.getElementById('scheduleModal'));
                scheduleModal.show();
            })
            .catch(error => {
                console.error('Error fetching schedule details:', error);
            });
    }

    function saveSchedule() {
        const scheduleId = document.getElementById('schedule_id').value;
        const scheduleData = {
            id_machine: document.getElementById('machine_id').value,
            id_operator: document.getElementById('operator_id').value,
            date: document.getElementById('date').value,
            damage_type: document.getElementById('damage_type').value,
            time_type: document.getElementById('time_type').value,
            status: document.getElementById('status').value
        };

        if (scheduleId) {
            // Update existing schedule in MySQL
            axios.put(`/api/schedule/${scheduleId}`, scheduleData)
                .then(response => {
                    fetchSchedules(); // Memperbarui data di tabel
                    closeModal(); // Menutup modal
                })
                .catch(error => {
                    console.error('Error updating schedule in MySQL:', error);
                });
        } else {
            // Add new schedule to MySQL
            axios.post('/api/schedule', scheduleData)
                .then(response => {
                    fetchSchedules(); // Memperbarui data di tabel
                    closeModal(); // Menutup modal
                })
                .catch(error => {
                    console.error('Error adding schedule to MySQL:', error);
                });
        }
    }

    function confirmDelete(scheduleId) {
        if (confirm('Are you sure you want to delete this schedule?')) {
            axios.delete(`/api/schedule/${scheduleId}`)
                .then(response => {
                    fetchSchedules(); // Memperbarui data di tabel
                })
                .catch(error => {
                    console.error('Error deleting schedule:', error);
                });
        }
    }

    function viewDetails(scheduleId) {
        axios.get(`/api/schedule/${scheduleId}`)
            .then(response => {
                if (response.data.success) {
                    const schedule = response.data.data;

                    // Mengisi detail jadwal ke dalam modal
                    document.getElementById('detailMachineName').innerText =
                        `Machine: ${schedule.id_machine}`;
                    document.getElementById('detailOperator').innerText =
                        `Operator: ${schedule.id_operator}`;
                    document.getElementById('detailDate').innerText =
                        `Date: ${new Date(schedule.date).toLocaleDateString('en-GB')}`;
                    document.getElementById('detailDamageType').innerText =
                        `Damage Type: ${schedule.damage_type}`;
                    document.getElementById('detailTimeType').innerText =
                        `Time Type: ${schedule.time_type}`;
                    document.getElementById('detailStatus').innerText =
                        `Status: ${schedule.status}`;

                    document.getElementById('scheduleModalLabel').innerText = 'View Details';
                    document.getElementById('saveScheduleButton').style.display = 'none';
                    document.getElementById('scheduleForm').style.display = 'none';
                    document.getElementById('scheduleDetails').style.display = 'block';

                    const scheduleModal = new bootstrap.Modal(document.getElementById('scheduleModal'));
                    scheduleModal.show();
                } else {
                    alert(response.data.message);
                }
            })
            .catch(error => {
                console.error("Fetch Error: ", error);
                alert('Error fetching schedule details');
            });
    }
</script>
