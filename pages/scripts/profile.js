import api from './api.js';

const profile = {
    async loadProfiles() {
        try {
            const result = await api.getAllProfiles();
            const profilesList = document.getElementById('profile-list-container');

            if (profilesList) {
                profilesList.innerHTML = ''; // clear existing profiles
                result.profiles.forEach(profile => {
                    const profileElement = document.createElement('div');
                    profileElement.className = 'user-profile';
                    profileElement.dataset.profileId = profile.profile_id;
                    profileElement.innerHTML = `
                        <div class="avatar-wrapper"
                            <div class="profile-avatar">
                                <img src="${profile.picture || 'assets/images/Profile/Profile1.png'}" alt="${profile.name}">
                            </div>
                            <span class="profile-name">${profile.name}</span>
                        </div>
                    `;

                    profileElement.addEventListener('click', () => this.handleProfileSelect(profile.profile_id));
                    profilesList.appendChild(profileElement);
                });
            }
        } catch (error) {
            console.error('Error loading profiles:', error);
            alert('Failed to load profiles. Please try again.');
        }
    },

    async handleProfileSelect(profileId) {
        try {
            const result = await api.setProfile(profileId);
            if (result.ok) {
                window.location.href = 'home.php';
            }
            else {
                throw new Error("Failed to set profile");
            }
        } catch (error) {
            console.error('Error selecting profile:', error);
            alert('Failed to select profile. Please try again.');
        }
    },

    init() {
        this.loadProfiles();

        const manageButton = document.getElementById('manage-profiles-btn');
        if (manageButton) {
            manageButton.addEventListener('click', () => {
                // TODO: Implement profile management
                alert('Profile management coming soon!');
            });
        }
    }
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    profile.init();
});
