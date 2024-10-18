<div class="row">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6>Repair Report</h6>
                    <div class="d-flex">
                        <input type="text" id="searchInput" class="form-control form-control-sm me-2"
                            placeholder="Search repair report..." onkeyup="searchRepair()"
                            style="height: 2.1rem; padding: 5px;">
                        <a href="javascript:;" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#repairModal" onclick="openAddModal()">Add</a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Machine</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Operator</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Repair Date</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Description</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Before</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        After</th>
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
                                {{-- Isi Datanya disini --}}
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

    <!-- Modal for Add/Edit Repair Report -->
    <div class="modal fade" id="repairModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Add/Edit/View Repair Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="repairForm">
                        <div class="mb-3">
                            <label for="machineName" class="form-label">Machine</label>
                            <input type="text" class="form-control" id="machineName" placeholder="Enter machine name"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="operatorName" class="form-label">Operator</label>
                            <input type="text" class="form-control" id="operatorName"
                                placeholder="Enter operator name" required>
                        </div>
                        <div class="mb-3">
                            <label for="repairDate" class="form-label">Repair Date</label>
                            <input type="date" class="form-control" id="repairDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" rows="3" placeholder="Enter description" required></textarea>
                        </div>
                        <div class="mb-3">
                          <label for="beforeImage" class="form-label">Condition Before</label>
                          <input type="file" class="form-control" id="beforeImage" accept="image/*" required>
                      </div>
                      <div class="mb-3">
                          <label for="afterImage" class="form-label">Condition After</label>
                          <input type="file" class="form-control" id="afterImage" accept="image/*" required>
                      </div>                      
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" required>
                                <option value="Ditunda">Ditunda</option>
                                <option value="Dalam_Proses">Dalam Proses</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>
                    </form>
                    <div id="repairDetails" style="display: none;">
                        <h6>Repair Details</h6>
                        <p><strong>Machine:</strong> <span id="detailMachineName"></span></p>
                        <p><strong>Operator:</strong> <span id="detailOperator"></span></p>
                        <p><strong>Repair Date:</strong> <span id="detailDate"></span></p>
                        <p><strong>Description:</strong> <span id="detailDescription"></span></p>
                        <p><strong>Condition Before:</strong> <span id="detailBefore"></span></p>
                        <p><strong>Condition After:</strong> <span id="detailAfter"></span></p>
                        <p><strong>Status:</strong> <span id="detailStatus"></span></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveRepairButton"
                        onclick="saveRepair()">Save</button>
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
        let repairReports = [];
        let originalRepairReports = [];

        document.addEventListener('DOMContentLoaded', function() {
            fetchRepairReports();
        });

        function fetchRepairReports() {
            axios.get('/api/repair-report')
                .then(response => {
                    repairReports = response.data.data; // Save all repair reports in the array
                    originalRepairReports = [...repairReports];
                    displayRepairReports(); // Display repair reports for the current page
                })
                .catch(error => {
                    console.error('Error fetching repair reports:', error);
                });
        }

        function displayRepairReports() {
            const tbody = document.querySelector('tbody');
            tbody.innerHTML = '';

            // Calculate start and end indices for the current page
            const start = (currentPage - 1) * recordsPerPage;
            const end = start + recordsPerPage;

            const paginatedRepairReports = repairReports.slice(start, end); // Get repair reports for the current page

            paginatedRepairReports.forEach(report => {
                const row = document.createElement('tr');
                row.innerHTML = `
                  <td class="align-middle text-center text-sm">${report.id_machine}</td>
                  <td class="align-middle text-center text-sm">${report.id_operator}</td>
                  <td class="align-middle text-center text-sm">${new Date(report.repair_date).toLocaleDateString('en-GB')}</td>
                  <td class="align-middle text-center">${report.description}</td>
                  <td class="align-middle text-center">${report.condition_before}</td>
                  <td class="align-middle text-center">${report.condition_after}</td>
                  <td class="align-middle text-center">${report.status}</td>
                  <td class="align-middle text-center">
                      <a href="javascript:;" class="text-secondary font-weight-bold text-xs"
                          onclick="openEditModal('${report.id_report}')">Edit</a>
                      &nbsp;|&nbsp;
                      <a href="javascript:;" class="text-danger font-weight-bold text-xs"
                          onclick="confirmDelete('${report.id_report}')">Delete</a>
                      &nbsp;|&nbsp;
                      <a href="javascript:;" class="text-secondary font-weight-bold text-xs"
                          onclick="viewDetails('${report.id_report}')">View</a>
                  </td>
              `;
                tbody.appendChild(row);
            });

            // Update pagination buttons
            updatePagination();
        }

        function updatePagination() {
            const pageCount = Math.ceil(repairReports.length / recordsPerPage);
            const prevButton = document.querySelector('.btn-primary[onclick="prevPage()"]');
            const nextButton = document.querySelector('.btn-primary[onclick="nextPage()"]');

            prevButton.disabled = currentPage === 1; // Disable prev button if on the first page
            nextButton.disabled = currentPage === pageCount || pageCount === 0; // Disable next button if on the last page
        }

        function nextPage() {
            const pageCount = Math.ceil(repairReports.length / recordsPerPage);
            if (currentPage < pageCount) {
                currentPage++;
                displayRepairReports();
            }
        }

        function prevPage() {
            if (currentPage > 1) {
                currentPage--;
                displayRepairReports();
            }
        }

        function searchRepair() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            currentPage = 1; // Reset to the first page on search

            if (query === '') {
                repairReports = [...originalRepairReports]; // Restore original repair reports if query is empty
            } else {
                repairReports = originalRepairReports.filter(report =>
                    report.id_machine.toLowerCase().includes(query) ||
                    report.id_operator.toLowerCase().includes(query) ||
                    new Date(report.repair_date).toLocaleDateString('en-GB').includes(query) ||
                    report.description.toLowerCase().includes(query) ||
                    report.condition_before.toLowerCase().includes(query) ||
                    report.condition_after.toLowerCase().includes(query) ||
                    report.status.toLowerCase().includes(query)
                );
            }
            displayRepairReports();
        }

        function openAddModal() {
            document.getElementById('repairForm').reset();
            document.getElementById('repair_id').value = '';
            document.getElementById('modalLabel').innerText = 'Add Repair Report';
            document.getElementById('saveRepairButton').style.display = 'inline-block';
            document.getElementById('repairForm').style.display = 'block';
            document.getElementById('repairDetails').style.display = 'none';
        }

        function closeModal() {
            const modal = new bootstrap.Modal(document.getElementById('repairModal'));
            modal.hide(); // Close modal
        }

        function openEditModal(reportId) {
            // Fetch report data from MySQL
            axios.get(`/api/repair-report/${reportId}`)
                .then(response => {
                    const report = response.data.data;
                    if (!report) {
                        console.error('Repair report not found:', reportId);
                        return;
                    }

                    // Populate form with fetched data
                    document.getElementById('repair_id').value = report.id_report;
                    document.getElementById('machineName').value = report.id_machine;
                    document.getElementById('operatorName').value = report.id_operator;
                    document.getElementById('repairDate').value = new Date(report.repair_date).toISOString().split('T')[
                        0];
                    document.getElementById('description').value = report.description;
                    document.getElementById('before').value = report.condition_before;
                    document.getElementById('after').value = report.condition_after;
                    document.getElementById('status').value = report.status;
                    document.getElementById('modalLabel').innerText = 'Edit Repair Report';
                    document.getElementById('saveRepairButton').style.display = 'inline-block';
                    document.getElementById('repairForm').style.display = 'block';
                    document.getElementById('repairDetails').style.display = 'none';

                    const repairModal = new bootstrap.Modal(document.getElementById('repairModal'));
                    repairModal.show();
                })
                .catch(error => {
                    console.error('Error fetching repair report details:', error);
                });
        }

        function saveRepair() {
            const reportId = document.getElementById('repair_id').value;
            const repairData = {
                id_machine: document.getElementById('machineName').value,
                id_operator: document.getElementById('operatorName').value,
                repair_date: document.getElementById('repairDate').value,
                description: document.getElementById('description').value,
                condition_before: document.getElementById('before').value,
                condition_after: document.getElementById('after').value,
                status: document.getElementById('status').value
            };

            if (reportId) {
                // Update existing repair report in MySQL
                axios.put(`/api/repair-report/${reportId}`, repairData)
                    .then(response => {
                        fetchRepairReports(); // Refresh table data
                        closeModal(); // Close modal
                    })
                    .catch(error => {
                        console.error('Error updating repair report in MySQL:', error);
                    });
            } else {
                // Add new repair report to MySQL
                axios.post('/api/repair-report', repairData)
                    .then(response => {
                        fetchRepairReports(); // Refresh table data
                        closeModal(); // Close modal
                    })
                    .catch(error => {
                        console.error('Error adding repair report to MySQL:', error);
                    });
            }
        }

        function confirmDelete(reportId) {
            if (confirm('Are you sure you want to delete this repair report?')) {
                axios.delete(`/api/repair-report/${reportId}`)
                    .then(response => {
                        fetchRepairReports(); // Refresh table data
                    })
                    .catch(error => {
                        console.error('Error deleting repair report:', error);
                    });
            }
        }

        function viewDetails(reportId) {
            axios.get(`/api/repair-report/${reportId}`)
                .then(response => {
                    if (response.data.success) {
                        const report = response.data.data;

                        // Populate details into modal
                        document.getElementById('detailMachineName').innerText = report.id_machine;
                        document.getElementById('detailOperator').innerText = report.id_operator;
                        document.getElementById('detailDate').innerText = new Date(report.repair_date)
                            .toLocaleDateString('en-GB');
                        document.getElementById('detailDescription').innerText = report.description;
                        document.getElementById('detailBefore').innerText = report.condition_before;
                        document.getElementById('detailAfter').innerText = report.condition_after;
                        document.getElementById('detailStatus').innerText = report.status;
                        
                        document.getElementById('modalLabel').innerText = 'View Details';
                        document.getElementById('saveRepairButton').style.display = 'none';
                        document.getElementById('repairForm').style.display = 'none';
                        document.getElementById('repairDetails').style.display = 'block';

                        const repairModal = new bootstrap.Modal(document.getElementById('repairModal'));
                        repairModal.show();

                        // Show modal
                        // $('#repairModal').modal('show');
                    } else {
                        alert(response.data.message);
                    }
                })
                .catch(error => {
                    console.error("Fetch Error: ", error);
                    alert('Error fetching repair report details');
                });
        }
    </script>
