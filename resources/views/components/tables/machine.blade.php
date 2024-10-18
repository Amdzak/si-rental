<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6>Machine</h6>
                <div class="d-flex">
                    <input type="text" id="searchInput" class="form-control form-control-sm me-2"
                        placeholder="Search machines..." onkeyup="searchMachines()" style="height: 2.1rem; padding: 5px;">
                    <a href="javascript:;" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#machineModal" onclick="openAddModal()">Add</a>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Machine
                                    Name</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Category</th>
                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Location</th>
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

<!-- Modal for Adding and Editing Machine -->
<div class="modal fade" id="machineModal" tabindex="-1" aria-labelledby="machineModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="machineModalLabel">Add/Edit/View Machine</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="machineForm">
                    <input type="hidden" id="machine_id">
                    <div class="mb-3">
                        <label for="machine_name" class="form-label">Machine</label>
                        <input type="text" class="form-control" id="machine_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-control" id="category" required>
                            <option value="Perkakas">Perkakas</option>
                            <option value="Produksi">Produksi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="location" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" required>
                            <option value="normal">Normal</option>
                            <option value="perlu_perbaikan">Perlu Perbaikan</option>
                            <option value="dalam_perbaikan">Dalam Perbaikan</option>
                            <option value="selesai_perbaikan">Selesai Diperbaiki</option>
                        </select>
                    </div>
                </form>
                <div id="machineDetails" style="display: none;">
                    <h6>Machine Details</h6>
                    <p id="detailMachineName"></p>
                    <p id="detailCategory"></p>
                    <p id="detailLocation"></p>
                    <p id="detailStatus"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveMachineButton" onclick="saveSchedule()">Save
                    changes</button>
            </div>
        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    let currentPage = 1;
    const recordsPerPage = 10;
    let machines = [];
    let originalMachines = [];
    document.addEventListener("DOMContentLoaded", function() {
        fetchMachines();
    });

    function fetchMachines() {
        axios.get('/api/machines')
            .then(response => {
                machines = response.data.data; // Save all machines in the array
                originalMachines = [...machines]; // Backup original data
                displayMachines(); // Display machines for the current page
            })
            .catch(error => {
                console.error('Error fetching machines:', error);
            });
    }

    function displayMachines() {
        const tbody = document.querySelector('tbody');
        tbody.innerHTML = '';

        // Calculate start and end indices for current page
        const start = (currentPage - 1) * recordsPerPage;
        const end = start + recordsPerPage;

        const paginatedSchedules = machines.slice(start, end); // Get schedules for the current page

        paginatedSchedules.forEach(machine => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="align-middle text-center text-sm">${machine.machine_name}</td>
                <td class="align-middle text-center text-sm">${machine.category}</td>
                <td class="align-middle text-center">${machine.location}</td>
                <td class="align-middle text-center">${machine.status}</td>
                <td class="align-middle text-center">
                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs"
                        onclick="openEditModal('${machine.id_machine}')">Edit</a>
                    &nbsp;|&nbsp;
                    <a href="javascript:;" class="text-danger font-weight-bold text-xs"
                        onclick="confirmDelete('${machine.id_machine}')">Delete</a>
                    &nbsp;|&nbsp;
                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs"
                        onclick="viewDetails('${machine.id_machine}')">View</a>
                </td>
            `;
            tbody.appendChild(row);
        });

        // Update pagination buttons
        updatePagination();
    }

    function updatePagination() {
        const pageCount = Math.ceil(machines.length / recordsPerPage);
        const prevButton = document.querySelector('.btn-primary[onclick="prevPage()"]');
        const nextButton = document.querySelector('.btn-primary[onclick="nextPage()"]');

        prevButton.disabled = currentPage === 1; // Disable prev button if on the first page
        nextButton.disabled = currentPage === pageCount || pageCount === 0; // Disable next button if on the last page
    }

    function nextPage() {
        const pageCount = Math.ceil(machines.length / recordsPerPage);
        if (currentPage < pageCount) {
            currentPage++;
            displayMachines();
        }
    }

    function prevPage() {
        if (currentPage > 1) {
            currentPage--;
            displayMachines();
        }
    }


    function searchMachines() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        currentPage = 1; // Reset to the first page on search

        if (query === '') {
            machines = [...originalMachines]; // Restore the original machines if the query is empty
        } else {
            machines = originalMachines.filter(machine =>
                machine.machine_name.toLowerCase().includes(query) ||
                machine.category.toLowerCase().includes(query) ||
                machine.location.toLowerCase().includes(query) ||
                machine.status.toLowerCase().includes(query)
            );
        }
        displayMachines();
    }


    function openAddModal() {
        document.getElementById('machineForm').reset();
        document.getElementById('machine_id').value = '';
        document.getElementById('machineModalLabel').innerText = 'Add Machine';
        document.getElementById('saveMachineButton').style.display = 'inline-block';
        document.getElementById('machineForm').style.display = 'block';
        document.getElementById('machineDetails').style.display = 'none';
    }

    function closeModal() {
        const modal = new bootstrap.Modal(document.getElementById('machineModal'));
        modal.hide(); // Menutup modal
    }


    function saveSchedule() {
        const id = document.getElementById('machine_id').value;
        const machineName = document.getElementById('machine_name').value;
        const category = document.getElementById('category').value;
        const location = document.getElementById('location').value;
        const status = document.getElementById('status').value;

        const url = id ? `/api/machines/${id}` : '/api/machines';
        const method = id ? 'put' : 'post';

        axios({
            method: method,
            url: url,
            data: {
                machine_name: machineName,
                category: category,
                location: location,
                status: status
            }
        }).then(response => {
            alert(response.data.message);
            fetchMachines(); // Refresh the machine list
            $('#machineModal').modal('hide'); // Hide the modal
        }).catch(error => {
            console.error(error);
            alert('Error saving machine');
        });
    }

    function openEditModal(id) {
        axios.get(`/api/machines/${id}`)
            .then(response => {
                if (response.data.success) {
                    const machine = response.data.data;
                    document.getElementById('machine_id').value = machine.id_machine;
                    document.getElementById('machine_name').value = machine.machine_name;
                    document.getElementById('category').value = machine.category;
                    document.getElementById('location').value = machine.location;
                    document.getElementById('status').value = machine.status;

                    document.getElementById('machineModalLabel').innerText = 'Edit Machine';
                    document.getElementById('saveMachineButton').style.display = 'inline-block';
                    document.getElementById('machineForm').style.display = 'block';
                    document.getElementById('machineDetails').style.display = 'none';

                    const machineModal = new bootstrap.Modal(document.getElementById('machineModal'));
                    machineModal.show();
                } else {
                    alert(response.data.message);
                }
            })
            .catch(error => {
                console.error(error);
                alert('Error fetching machine details'); // Debugging message
            });
    }

    function deleteMachine(id) {
        if (confirm('Are you sure you want to delete this machine?')) {
            axios.delete(`/api/machines/${id}`)
                .then(response => {
                    alert(response.data.message);
                    fetchMachines(); // Refresh the machine list
                })
                .catch(error => {
                    console.error(error);
                    alert('Error deleting machine');
                });
        }
    }

    function viewDetails(id) {
        console.log("Fetching details for ID: ", id); // Log ID yang digunakan
        axios.get(`/api/machines/${id}`)
            .then(response => {
                if (response.data.success) {
                    const machine = response.data.data;

                    // Mengisi detail mesin ke dalam modal
                    document.getElementById('detailMachineName').innerText =
                        `Machine Name: ${machine.machine_name}`;
                    document.getElementById('detailCategory').innerText = `Category: ${machine.category}`;
                    document.getElementById('detailLocation').innerText = `Location: ${machine.location}`;
                    document.getElementById('detailStatus').innerText = `Status: ${machine.status}`;

                    document.getElementById('machineModalLabel').innerText = 'View Details';
                    document.getElementById('saveMachineButton').style.display = 'none';
                    document.getElementById('machineForm').style.display = 'none';
                    document.getElementById('machineDetails').style.display = 'block';

                    const machineModal = new bootstrap.Modal(document.getElementById('machineModal'));
                    machineModal.show();
                } else {
                    alert(response.data.message);
                }
            })
            .catch(error => {
                console.error("Fetch Error: ", error); // Tampilkan error di konsol
                alert('Error fetching machine details: ' + (error.response ? error.response.data.message : error
                    .message));
            });
    }
</script>
