import api from './api.js';

class AccountManager {
    constructor() {
        this.profilesGrid = document.getElementById('profiles-grid');
        this.addProfileBtn = document.getElementById('add-profile-btn');
        this.deleteAccountBtn = document.getElementById('delete-account-btn');

        // Modals
        this.addProfileModal = document.getElementById('add-profile-modal');
        this.editProfileModal = document.getElementById('edit-profile-modal');
        this.deleteConfirmationModal = document.getElementById('delete-confirmation-modal');

        // Forms
        this.addProfileForm = document.getElementById('add-profile-form');
        this.editProfileForm = document.getElementById('edit-profile-form');

        // Initialize
        this.init();
    }

    async init() {
        // Load current user data
        try {
            const userData = await api.getMe();
            this.currentProfileId = userData.profile_id;
            await this.loadProfiles();
        } catch (error) {
            console.error('Error loading user data:', error);
        }

        // Event listeners
        this.addProfileBtn.addEventListener('click', () => this.showModal(this.addProfileModal));
        this.deleteAccountBtn.addEventListener('click', () => this.showDeleteConfirmation());

        // Form submissions
        this.addProfileForm.addEventListener('submit', (e) => this.handleAddProfile(e));
        this.editProfileForm.addEventListener('submit', (e) => this.handleEditProfile(e));

        // Modal close buttons
        document.querySelectorAll('.cancel-btn').forEach(btn => {
            btn.addEventListener('click', () => this.hideAllModals());
        });
    }

    async loadProfiles() {
        try {
            const result = await api.getAllProfiles();
            this.profilesGrid.innerHTML = '';

            result.profiles.forEach(profile => {
                const profileCard = this.createProfileCard(profile);
                this.profilesGrid.appendChild(profileCard);
            });
        } catch (error) {
            console.error('Error loading profiles:', error);
        }
    }

    createProfileCard(profile) {
        const card = document.createElement('div');
        card.className = 'profile-card';
        card.innerHTML = `
            <img src="${profile.picture || 'assets/images/Profile/Profile1.png'}" alt="${profile.name}">
            <div class="profile-name">${profile.name}</div>
            <div class="profile-actions">
                <button class="edit-btn" data-profile-id="${profile.profile_id}">
                    <i class="fa fa-pencil"></i>
                </button>
                <button class="delete-btn" data-profile-id="${profile.profile_id}">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        `;

        // Profile selection
        card.addEventListener('click', (e) => {
            if (!e.target.closest('.profile-actions')) {
                this.handleProfileSelect(profile.profile_id);
            }
        });

        // Edit button
        card.querySelector('.edit-btn').addEventListener('click', (e) => {
            e.stopPropagation();
            this.showEditModal(profile);
        });

        // Delete button
        card.querySelector('.delete-btn').addEventListener('click', (e) => {
            e.stopPropagation();
            this.showDeleteConfirmation(profile.profile_id);
        });

        return card;
    }

    async handleProfileSelect(profileId) {
        try {
            const result = await api.setProfile(profileId);
            if (result.ok) {
                window.location.href = 'home.php';
            }
        } catch (error) {
            console.error('Error switching profile:', error);
        }
    }

    async handleAddProfile(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const name = formData.get('name');

        try {
            const result = await api.addProfile(name);
            if (result.ok) {
                this.hideAllModals();
                await this.loadProfiles();
            }
        } catch (error) {
            console.error('Error adding profile:', error);
        }
    }

    showEditModal(profile) {
        const form = this.editProfileForm;
        form.querySelector('[name="profile_id"]').value = profile.profile_id;
        form.querySelector('[name="name"]').value = profile.name;
        this.showModal(this.editProfileModal);
    }

    async handleEditProfile(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const profileId = formData.get('profile_id');
        const name = formData.get('name');

        try {
            const result = await api.updateProfile(profileId, name);
            if (result.ok) {
                this.hideAllModals();
                await this.loadProfiles();
            }
        } catch (error) {
            console.error('Error updating profile:', error);
        }
    }

    showDeleteConfirmation(profileId) {
        this.showModal(this.deleteConfirmationModal);
        const deleteBtn = this.deleteConfirmationModal.querySelector('.delete-btn');
        if (profileId) {
            // If profileId is provided, we're deleting a profile
            deleteBtn.onclick = () => this.handleDeleteProfile(profileId);
        } else {
            // If no profileId, we're deleting the account
            deleteBtn.onclick = () => this.handleDeleteAccount();
        }
    }

    async handleDeleteProfile(profileId) {
        try {
            console.log('profileId', profileId);
            const result = await api.deleteProfile(profileId);
            if (result.ok) {
                this.hideAllModals();
                await this.loadProfiles();
            }
        } catch (error) {
            console.error('Error deleting profile:', error);
            // Show error message to user
            const errorMessage = document.createElement('div');
            errorMessage.className = 'error-message';
            errorMessage.textContent = 'Failed to delete profile. Please try again.';
            this.profilesGrid.appendChild(errorMessage);
            setTimeout(() => errorMessage.remove(), 3000);
        }
    }

    async handleDeleteAccount() {
        try {
            console.log('Attempting to delete account...');
            const result = await api.deleteUser();
            console.log('Delete account result:', result);
            if (result.ok) {
                this.hideAllModals();
                window.location.href = 'signin.php';
            } else {
                // Show error message to user
                const errorMessage = document.createElement('div');
                errorMessage.className = 'error-message';
                errorMessage.textContent = 'Failed to delete account. Please try again.';
                this.profilesGrid.appendChild(errorMessage);
                setTimeout(() => errorMessage.remove(), 3000);
            }
        } catch (error) {
            console.error('Error deleting account:', error);
            // Show error message to user
            const errorMessage = document.createElement('div');
            errorMessage.className = 'error-message';
            errorMessage.textContent = 'Failed to delete account. Please try again.';
            this.profilesGrid.appendChild(errorMessage);
            setTimeout(() => errorMessage.remove(), 3000);
        }
    }

    showModal(modal) {
        this.hideAllModals();
        modal.classList.add('active');
    }

    hideAllModals() {
        document.querySelectorAll('.modal').forEach(modal => {
            modal.classList.remove('active');
        });
    }
}

// Initialize the account manager when the page loads
document.addEventListener('DOMContentLoaded', () => {
    new AccountManager();
}); 
