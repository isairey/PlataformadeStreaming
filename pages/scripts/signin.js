import api from './api.js';

const signin = {
    async handleSignin(event) {
        event.preventDefault();
        const email = document.getElementById('email-input').value;
        const password = document.getElementById('password-input').value;

        try {
            await api.authenticate(email, password, document.getElementById('remember-me').checked);
            const profiles = await api.getAllProfiles();
            if (profiles.profiles && profiles.profiles.length > 1) {
                window.location.href = 'profile.php';
            } else if (profiles.profiles) {
                const profileResult = await api.setProfile(profiles.profiles[0].profile_id);
                if (profileResult.ok) {
                    window.location.href = 'home.php';
                }
                else {
                    throw new Error("Failed to set profile");
                }
            }
            else {
                throw new Error("Failed to fetch profiles");
            }
        } catch (error) {
            console.error('Signin error:', error);
            alert(error.message || 'Failed to sign in. Please try again.');
        }
    },

    init() {
        const form = document.getElementById('signin-form');
        if (form) {
            form.addEventListener('submit', this.handleSignin.bind(this));
        }
    }
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    signin.init();
});
