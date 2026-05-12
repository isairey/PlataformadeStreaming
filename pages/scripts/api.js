const API_BASE_URL = '/nyetflix/api';

const TOKEN_KEY = 'auth_token';
const TOKEN_EXP_KEY = `${TOKEN_KEY}_expires`;

const api = {
    getToken() {
        const cookies = document.cookie.split('; ');
        for (const cookie of cookies) {
            const [name, value] = cookie.split('=');
            if (name === TOKEN_KEY) return value;
        }
        return null;
    },

    setToken(token, exp_days = null) {
        let cookie = `${TOKEN_KEY}=${token}`;
        if(exp_days !== null && exp_days > 0) {
            const exp = new Date();
            exp.setDate(exp.getDate() + exp_days);
            cookie += `; expires=${exp.toUTCString()}`;
            sessionStorage.setItem(TOKEN_EXP_KEY, exp.getTime());
        }
        else {
            const savedExp = sessionStorage.getItem(TOKEN_EXP_KEY);
            if (savedExp) {
                const exp = new Date(parseInt(savedExp));
                cookie += `; expires=${exp.toUTCString()}`;
            }
        }
        document.cookie = `${cookie}; path=/; SameSite=Strict`;
    },

    clearToken() {
        document.cookie = `${TOKEN_KEY}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/`;
    },

    async request(endpoint, data = null) {
        console.log(endpoint, data);
        const headers = {
            'Content-Type': 'application/json'
        };

        const options = {
            method : 'POST',
            headers,
            body: data ? JSON.stringify(data) : null
        };

        try {
            const response = await fetch(`${API_BASE_URL}/${endpoint}`, options);
            const result = await response.json();

            if (!result.ok) {
                throw new Error(result.message);
            }

            return result;
        } catch (error) {
            console.error('API request error:', error);
            throw error;
        }
    },

    // Authentication methods
    async subscribe(name, email, password) {
        const result = await this.request('subscribe.php', { name : name, email : email, password : password });
        this.setToken(result.token);
        return result;
    },

    async authenticate(email, password, remember_me = false) {
        const result = await this.request('authenticate.php', { email : email, password : password });
        this.setToken(result.token, remember_me ? 30 : 0);
        return result;
    },

    // Profile methods
    async addProfile(name) {
        return this.request('addProfile.php', { name });
    },

    async setProfile(profileId) {
        const result = await this.request('setProfile.php', profileId);
        this.setToken(result.token);
        return result;
    },

    async getAllProfiles() {
        return this.request('getAllProfiles.php');
    },

    async deleteProfile(profileId) {
        return this.request('deleteProfile.php', profileId);
    },

    async updateProfile(profileId, new_name) {
        return this.request('updateProfile.php', { profile_id : profileId, name : new_name });
    },

    // Content methods
    async getFilms() {
        return this.request('getFilms.php');
    },

    async getSeries() {
        return this.request('getSeries.php');
    },

    async getMovie(movieId) {
        return this.request('getMovie.php', movieId);
    },

    async getSeason(movieId, seasonNo) {
        return this.request('getSeason.php', { movie_id: movieId, season_no: seasonNo });
    },

    async getRecents() {
        return this.request('getRecents.php');
    },

    async getGenre(genre) {
        return this.request('getGenre.php', genre);
    },

    // My List methods
    async getMyList() {
        return this.request('getMyList.php');
    },

    async addToList(movieId) {
        return this.request('addToList.php', Number(movieId));
    },

    async removeFromList(movieId) {
        return this.request('removeFromList.php', Number(movieId));
    },

    // User information
    async getMe() {
        return this.request('getMe.php');
    },

    async pickRandomMovies(count) {
        return this.request('pickRandomMovies.php', count);
    },

    // Watch history
    async getHistory() {
        return this.request('getHistory.php');
    },

    async watchMovie(movieId) {
        return this.request('watchMovie.php', Number(movieId));
    },

    async getNextPlay(movieId) {
        return this.request('getNextPlay.php', Number(movieId));
    },

    async reportProgress(movieId, position) {
        return this.request('reportProgress.php', {movie_id : movieId, position : position});
    },

    async deleteUser() {
        const result = await this.request('deleteUser.php');
        this.clearToken();
        return result;
    }
};

export default api;
