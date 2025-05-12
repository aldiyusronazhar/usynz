<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Team</title>
    <link href="css/main.css" rel="stylesheet">
</head>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const tbody = document.querySelector('tbody');
        const modal = document.getElementById('userModal');
        const modalForm = document.getElementById('userForm');
        const closeModalButton = document.querySelector('.close');
        const cancelModalButton = document.querySelector('.btn-cancel');
        const addButton = document.querySelector('.add_btn');
        const userIdInput = document.getElementById('userId');
        const sortButtonASC = document.querySelector('.filter button.asc');
        const sortButtonDESC = document.querySelector('.filter button.desc');
        const searchInput = document.querySelector('.filter input[type="text"]');

        searchInput.addEventListener('input', () => {
            const searchTerm = searchInput.value.toLowerCase();
            const filteredUsers = usersData.filter(user => {
                return user.name.toLowerCase().includes(searchTerm) ||
                    user.email.toLowerCase().includes(searchTerm);
            });
            displayUsers(filteredUsers);
        });

        let usersData = [];
        let isAsc = false; // Default (desc)

        addButton.addEventListener('click', () => showModal('add'));

        closeModalButton.addEventListener('click', () => closeModal());
        cancelModalButton.addEventListener('click', () => closeModal());

        modalForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const age = document.getElementById('age').value;
            const phoneNumber = document.getElementById('phone_number').value;

            if (!name || !email || !age || !phoneNumber) {
                alert("All fields are required.");
                return;
            }

            const userData = {name, email, age, phone_number: phoneNumber};

            // If it's editing an existing user
            if (userIdInput.value) {
                try {
                    const res = await fetch(`/api/users/${userIdInput.value}`, {
                        method: 'PUT',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify(userData),
                    });
                    if (res.ok) {
                        alert('User updated successfully!');
                        location.reload();
                    } else {
                        const data = await res.json();
                        alert(`Failed to update user: ${data.message}`);
                    }
                } catch (err) {
                    console.error(err);
                    alert('Error updating user.');
                }
            } else {
                // If it's adding a new user
                try {
                    const res = await fetch('/api/users', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify(userData),
                    });
                    if (res.ok) {
                        alert('User added successfully!');
                        location.reload();
                    } else {
                        const data = await res.json();
                        alert(`Failed to add user: ${data.message}`);
                    }
                } catch (err) {
                    console.error(err);
                    alert('Error adding user.');
                }
            }
            closeModal();
        });

        try {
            const res = await fetch('/api/users');
            if (!res.ok) throw new Error(`HTTP error! Status: ${res.status}`);

            usersData = await res.json();

            const sortedUsers = usersData.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
            displayUsers(sortedUsers);

        } catch (err) {
            console.error('Error fetching users:', err);
        }

        sortButtonASC.addEventListener('click', () => {
            isAsc = true;
            const sortedUsers = [...usersData].sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
            displayUsers(sortedUsers);
        });

        sortButtonDESC.addEventListener('click', () => {
            isAsc = false;
            const sortedUsers = [...usersData].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
            displayUsers(sortedUsers);
        });

        // Function to download CSV
        const downloadCSV = () => {
            const rows = [
                ['No', 'Name', 'Email', 'Age', 'Created', 'Updated'] // header
            ];

            usersData.forEach((user, index) => {
                rows.push([
                    index + 1,
                    user.name,
                    user.email,
                    user.age,
                    new Date(user.created_at).toLocaleDateString(),
                    new Date(user.updated_at).toLocaleString()
                ]);
            });

            const csvContent = rows.map(row => row.join(',')).join('\n');
            const blob = new Blob([csvContent], {type: 'text/csv;charset=utf-8;'});
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'users_data.csv';
            link.click();
        };

        // Event listener for CSV download
        const csvButton = document.querySelector('.top_right button:first-child');
        csvButton.addEventListener('click', downloadCSV);

        function displayUsers(users) {
            tbody.innerHTML = ''; // clear loading state
            const fragment = document.createDocumentFragment();

            users.forEach((user, index) => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${user.name}<br><span class="phone">${user.phone_number ?? '-'}</span></td>
                    <td>${user.email}</td>
                    <td><span class="badge">${user.age}</span></td>
                    <td>${new Date(user.created_at).toLocaleDateString()}<br>
                        <span class="time">${new Date(user.updated_at).toLocaleString()}</span>
                    </td>
                    <td>
                        <button class="edit" data-id="${user.id}">Edit</button>
                        <button class="delete" data-id="${user.id}">Delete</button>
                    </td>
                `;
                tr.querySelector('.edit').addEventListener('click', () => showModal('edit', user.id));
                tr.querySelector('.delete').addEventListener('click', () => deleteUser(user.id));

                fragment.appendChild(tr);
            });

            tbody.appendChild(fragment);
        }
    });

    function showModal(mode, userId = null) {
        const modal = document.getElementById('userModal');
        const userIdInput = document.getElementById('userId');
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        const ageInput = document.getElementById('age');
        const phoneInput = document.getElementById('phone_number');

        if (mode === 'add') {
            modal.querySelector('h2').textContent = 'Add New User';
            userIdInput.value = '';
            nameInput.value = '';
            emailInput.value = '';
            ageInput.value = '';
            phoneInput.value = '';
        } else if (mode === 'edit') {
            modal.querySelector('h2').textContent = 'Edit User';
            userIdInput.value = userId;

            // Fetch user data and fill the form
            fetch(`/api/users/${userId}`)
                .then(res => res.json())
                .then(data => {
                    nameInput.value = data.name;
                    emailInput.value = data.email;
                    ageInput.value = data.age;
                    phoneInput.value = data.phone_number;
                });
        }

        modal.style.display = 'block';
    }

    function closeModal() {
        const modal = document.getElementById('userModal');
        modal.style.display = 'none';
    }

    function deleteUser(id) {
        if (!confirm("Are you sure you want to delete this user?")) return;

        fetch(`/api/users/${id}`, {method: 'DELETE'})
            .then(res => res.json())
            .then(data => {
                alert('User deleted successfully!');
                location.reload();
            })
            .catch(err => {
                console.error('Error deleting user:', err);
                alert('Failed to delete user.');
            });
    }
</script>

<body>
    <div class="container">
        <div class="top">
            <div class="top_left">
                <h1>Manage Your Team</h1>
                <p>A simple web application to manage your team members:.</p>
            </div>
            <div class="top_right">
                <button>CSV</button>
                <button class="add_btn">Add Member</button>
            </div>
        </div>

        <div class="toolbar">
            <div class="filter">
                <input type="text" placeholder="Search Here...">
                <button class="asc">ASC</button>
                <button class="desc">DSC</button>
            </div>
            <div class="info">
                <p>Limit requests to avoid egress costs! <span>Trial users may experience slower requests.</span></p>
            </div>
        </div>

        <div class="table-container">
            <table class="team-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Age</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- User data will be injected here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Popup for Add/Edit User -->
    <div id="userModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add New User</h2>
            <form id="userForm">
                <input type="hidden" id="userId">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" required pattern="[A-Za-z\s]+"
                        title="Name should only contain letters">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" required>
                </div>
                <div class="form-group">
                    <label for="age">Age:</label>
                    <input type="number" id="age" required>
                </div>
                <div class="form-group">
                    <label for="phone_number">Phone Number:</label>
                    <input type="text" id="phone_number" required minlength="10">
                </div>
                <div class="button-group">
                    <button type="button" class="btn-cancel">cancel</button>
                    <button type="submit" class="btn">Save</button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>
