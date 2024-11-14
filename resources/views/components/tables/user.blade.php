<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6>All Users</h6>
                <div class="d-flex">
                    <input type="text" id="searchInput" class="form-control form-control-sm me-2"
                        placeholder="Search user..." onkeyup="searchUser()" style="height: 2.1rem; padding: 5px;">
                    <a href="javascript:;" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#userModal" onclick="openAddModal()">Add</a>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    No.
                                </th>
                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Name
                                </th>
                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Username
                                </th>
                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    role
                                </th>
                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Creation Date
                                </th>
                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Disini isinya --}}
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

<!-- Modal for Adding and Editing user -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Add/Edit/View User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="userForm">
                    <input type="hidden" id="user_id">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" required>
                            <button
                                class="btn shadow-none border-start border-end rounded-2 z-3 position-absolute top-0 end-0"
                                type="button" onclick="togglePasswordVisibility()">
                                <i id="toggleIcon" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-control" id="role" required>
                            <option value="1">Admin</option>
                            <option value="2">Pegawai</option>
                        </select>
                    </div>
                </form>
                <div id="userDetails" style="display: none;">
                    <h6>User Details</h6>
                    <p id="detailMachine"></p>
                    <p id="detailuserName"></p>
                    <p id="detailQuantity"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveUserButton" onclick="saveUser()">Save
                    changes</button>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    let currentPage = 1;
    const recordsPerPage = 10;
    let user = [];
    let originalUser = [];
    document.addEventListener("DOMContentLoaded", function () {
        fetchUser();
    });

    function fetchUser() {
        axios.get('/api/user')
            .then(response => {
                user = response.data.data; // Save all user in the array
                originalUser = [...user]; // Backup original data
                displayUser(); // Display user for the current page
            })
            .catch(error => {
                console.error('Error fetching user:', error);
            });
    }

    function displayUser() {
        const tbody = document.querySelector('tbody');
        tbody.innerHTML = '';

        // Calculate start and end indices for current page
        const start = (currentPage - 1) * recordsPerPage;
        const end = start + recordsPerPage;

        const paginatedUser = user.slice(start, end); // Get user for the current page

        paginatedUser.forEach((user, index) => { // Tambahkan index di parameter forEach
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="align-middle text-center text-sm">${start + index + 1}</td>
                <td class="align-middle text-center text-sm">${user.name}</td>
                <td class="align-middle text-center text-sm">${user.username}</td>
                <td class="align-middle text-center text-sm">${user.role == 1 ? "Admin" : 'Pegawai'} </td>
                <td class="align-middle text-center text-sm">${new Date(user.created_at).toLocaleDateString('en-GB', { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' })}</td>

                <td class="align-middle text-center">
                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs"
                        onclick="openEditModal('${user.user_id}')">Edit</a> |
                    <a href="javascript:;" class="text-danger font-weight-bold text-xs"
                        onclick="deleteUser('${user.user_id}')">Delete</a>
                </td>
            `;
            tbody.appendChild(row);
        });

        // Update pagination buttons
        updatePagination();
    }


    function updatePagination() {
        const pageCount = Math.ceil(user.length / recordsPerPage);
        const prevButton = document.querySelector('.btn-primary[onclick="prevPage()"]');
        const nextButton = document.querySelector('.btn-primary[onclick="nextPage()"]');

        prevButton.disabled = currentPage === 1; // Disable prev button if on the first page
        nextButton.disabled = currentPage === pageCount || pageCount === 0; // Disable next button if on the last page
    }

    function nextPage() {
        const pageCount = Math.ceil(user.length / recordsPerPage);
        if (currentPage < pageCount) {
            currentPage++;
            displayUser();
        }
    }

    function prevPage() {
        if (currentPage > 1) {
            currentPage--;
            displayUser();
        }
    }

    function searchUser() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        currentPage = 1; // Reset to the first page on search

        if (query === '') {
            user = [...originalUser]; // Restore the original user if the query is empty
        } else {
            user = originalUser.filter(user => {
                // Konversi nilai role ke teks sebelum pencarian
                const roleText = user.role == 1 ? "admin" : "pegawai";
                return (
                    user.username.toLowerCase().includes(query) ||
                    user.name.toLowerCase().includes(query) ||
                    roleText.includes(query) // Cari dengan teks role
                );
            });
        }
        displayUser();
    }


    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }

    function openAddModal() {
        document.getElementById('userForm').reset();
        document.getElementById('user_id').value = '';
        document.getElementById('userModalLabel').innerText = 'Add User';
        document.getElementById('saveUserButton').style.display = 'inline-block';
        document.getElementById('userForm').style.display = 'block';
        document.getElementById('userDetails').style.display = 'none';
    }

    function saveUser() {
        const id = document.getElementById('user_id').value;
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        const name = document.getElementById('name').value;
        const role = document.getElementById('role').value;

        const url = id ? `/api/user/${id}` : '/api/user';
        const method = id ? 'put' : 'post';

        axios({
            method: method,
            url: url,
            data: {
                username: username,
                password: password,
                name: name,
                role: role
            }
        }).then(response => {
            alert(response.data.message);
            fetchUser(); // Refresh the user list
            $('#userModal').modal('hide'); // Hide the modal
        }).catch(error => {
            console.error(error);
            alert('Error saving user');
        });
    }

    function openEditModal(id) {
        axios.get(`/api/user/${id}`)
            .then(response => {
                if (response.data.success) {
                    const user = response.data.data;
                    document.getElementById('user_id').value = user.user_id;
                    document.getElementById('username').value = user.username;
                    document.getElementById('password').value = '';
                    document.getElementById('name').value = user.name;
                    document.getElementById('role').value = user.role;

                    document.getElementById('userModalLabel').innerText = 'Edit User';
                    document.getElementById('saveUserButton').style.display = 'inline-block';
                    document.getElementById('userForm').style.display = 'block';
                    document.getElementById('userDetails').style.display = 'none';

                    const userModal = new bootstrap.Modal(document.getElementById('userModal'));
                    userModal.show();
                } else {
                    alert(response.data.message);
                }
            })
            .catch(error => {
                console.error(error);
                alert('Error fetching user details');
            });
    }

    function deleteUser(id) {
        if (confirm('Are you sure you want to delete this user?')) {
            axios.delete(`/api/user/${id}`)
                .then(response => {
                    alert(response.data.message);
                    fetchUser(); // Refresh the user list
                })
                .catch(error => {
                    console.error(error);
                    alert('Error deleting user');
                });
        }
    }

</script>