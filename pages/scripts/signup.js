import api from './api.js';

const signup = {
    async handleSignup(event) {
        event.preventDefault();

        const name = document.getElementById('name-input').value;
        const username = document.getElementById('email-input').value;
        const password = document.getElementById('password-input').value;

        try {
            await api.subscribe(name, username, password);
            const profiles = await api.getAllProfiles();
            await api.setProfile(profiles.profiles[0].profile_id);
            window.location.href = 'home.php';
        } catch (error) {
            console.error('Signup error:', error);
            alert(error.message || 'Failed to sign up. Please try again.');
        }
    },

    init() {
        const form = document.getElementById('signup-form');
        if (form) {
            form.addEventListener('submit', this.handleSignup.bind(this));
        }
    }
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    signup.init();
});
