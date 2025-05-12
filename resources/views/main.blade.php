<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Team</title>
    <link href="css/main.css" rel="stylesheet">
    <style>
        /* Style Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #111;
            padding: 20px;
            border: 1px solid #333;
            border-radius: 7px;
            width: 80%;
            max-width: 400px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: fadeIn 0.5s ease-in-out;
        }

        .modal-content h2 {
            margin-bottom: 20px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
            width: 100%;
            gap: 5px;
        }

        .form-group input {
            background-color: #0b0b0b;
            color: #fff;
            width: 100%;
            border: 1px solid #333;
            padding: 7px 19px;
            transition: 0.4s;
            font-size: 18px;
            border-radius: 7px;
            transition: 0.4s;
            height: 50px;
        }

        .form-group input:focus {
            outline: 1px solid rgba(254, 61, 0, 0.1);
            border: 1px solid rgba(254, 61, 0, 0.5);
        }

        .form-group label {
            font-size: 14px;
        }

        .button-group {
            display: flex;
            gap: 10px;
        }

        .btn-cancel {
            background-color: #0b0b0b;
            color: #fff;
            padding: 10px 20px;
            border: 1px solid rgba(255, 0, 0, 0.5);
            cursor: pointer;
            border-radius: 4px;
            width: 100%;
            transition: 0.4s;
        }

        .btn {
            background-color: #0b0b0b;
            color: white;
            padding: 10px 20px;
            border: 1px solid #333;
            cursor: pointer;
            border-radius: 4px;
            width: 100%;
            transition: 0.4s;
        }

        .btn:hover {
            background-color: #333;
            color: #fff;
        }

        .btn-cancel:hover {
            background-color: #ff0000;
            color: #fff;
        }

        .team-table th,
        .team-table td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const tbody = document.querySelector('tbody');
        const modal = document.getElementById('userModal');
        const modalForm = document.getElementById('userForm');
        const closeModalButton = document.querySelector('.close');
        const cancelModalButton = document.querySelector('.btn-cancel');
        const addButton = document.querySelector('.top_right button'); // Button untuk tambah member
        const userIdInput = document.getElementById('userId');

        const sortButtonASC = document.querySelector('.filter button.asc');
        const sortButtonDESC = document.querySelector('.filter button.desc');
        let usersData = [];
        let isAsc = false; // Default urutkan terbaru (desc)

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

            // Sort default by created_at DESC (terbaru)
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
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
            </div>
            <div class="top_right">
                <button>Add Member</button>
            </div>
        </div>

        <div class="toolbar">
            <div class="filter">
                <input type="text" placeholder="Search Here...">
                <button class="asc">ASC</button>
                <button class="desc">DSC</button>
            </div>
            <div class="info">
                <p>Please Lorem ipsum dolor sit Lorem, ipsum..</p>
            </div>
        </div>

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
                    <!-- <input type="text" id="phone_number" required> -->
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
