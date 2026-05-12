import api from './api.js';

const ui = {
    showLoading(element) {
        element.classList.add('loading');
        element.style.opacity = '0.5';
        element.style.pointerEvents = 'none';
    },

    hideLoading(element) {
        element.classList.remove('loading');
        element.style.opacity = '1';
        element.style.pointerEvents = 'auto';
    },

    showError(message, element) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        element.appendChild(errorDiv);
        setTimeout(() => errorDiv.remove(), 5000);
    },

    createContentRow(title, items) {
        const row = document.createElement('div');
        row.className = 'content-row column';

        const titleDiv = document.createElement('div');
        titleDiv.className = 'title';
        titleDiv.textContent = title;
        row.appendChild(titleDiv);

        const slider = document.createElement('div');
        slider.className = 'slider';

        items.forEach(movie => {
            const content = document.createElement('div');
            content.className = 'content';
            content.innerHTML = this.createMovieCard(movie);
            slider.appendChild(content);
        });

        row.appendChild(slider);
        return row;
    },

    // movie card factory
    createMovieCard(movie) {
        return `
            <div class="wrapper__front">
                <img src="${movie.thumbnail || movie.image}" alt="${movie.title}" />
            </div>
            <div class="wrapper__back">
                <div class="card__header">
                    <img src="${movie.thumbnail || movie.image}" alt="${movie.title}" />
                </div>
                <div class="card__body">
                    <div class="flex justify-between items-center">
                        <div>
                            <button class="btn btn--transparent btn--circle play-btn" data-id="${movie.movie_id || movie.id}">
                                <svg class="card__icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path d="M21.44 10.72L5.96 2.98A1.38 1.38 0 004 4.213v15.474a1.373 1.373 0 002 1.233l15.44-7.74a1.38 1.38 0 000-2.467v.007z" />
                                </svg>
                            </button>
                            <button class="btn btn--transparent btn--circle add-btn" data-id="${movie.movie_id || movie.id}">
                                <svg class="card__icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path d="M12 0a1.5 1.5 0 011.5 1.5v9h9a1.5 1.5 0 110 3h-9v9a1.5 1.5 0 11-3 0v-9h-9a1.5 1.5 0 110-3h9v-9A1.5 1.5 0 0112 0z" />
                                </svg>
                            </button>
                        </div>
                        <button class="btn btn--transparent btn--circle info-btn" data-id="${movie.movie_id || movie.id}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="card__icon" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M2.469 6.969a.75.75 0 011.062 0L12 15.439l8.469-8.47a.75.75 0 111.062 1.062l-9 9a.75.75 0 01-1.062 0l-9-9a.75.75 0 010-1.062z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    <p class="card__title text">
                        <span class="text--bold">${movie.type === 2 ? `S${movie.season_no || 1}:E${movie.episode_no || 1}` : ''}</span>
                        ${movie.title}
                    </p>
                    <div class="card__progress flex justify-between items-center">
                        <div class="progressbar">
                            <div class="progressbar__status" style="width: ${movie.progress || 0}%"></div>
                        </div>
                        <span class="text text--bold text--muted">${movie.duration || ''}</span>
                    </div>
                </div>
            </div>
        `;
    },

    // load and display content
    async loadContent() {
        const contentRows = document.getElementById('content-rows');
        this.showLoading(contentRows);

        try {
            // load categories
            const [recentRes, filmsRes, seriesRes, myListRes] = await Promise.all([
                api.getRecents(),
                api.getFilms(),
                api.getSeries(),
                api.getMyList()
            ]);

            contentRows.innerHTML = '';

            if (recentRes.movies && recentRes.movies.length > 0) {
                contentRows.appendChild(this.createContentRow('Get In on the Action', recentRes.movies));
            }
            if (filmsRes.films && filmsRes.films.length > 0) {
                contentRows.appendChild(this.createContentRow('New on Netflix', filmsRes.films));
            }
            if (seriesRes.series && seriesRes.series.length > 0) {
                contentRows.appendChild(this.createContentRow('Golden Globe Award-winning TV Comedies', seriesRes.series));
            }
            if (myListRes.items && myListRes.items.length > 0) {
                contentRows.appendChild(this.createContentRow('My List', myListRes.items));
            }

            this.setupMovieCardListeners();
        } catch (error) {
            console.error('Failed to load content:', error);
            this.showError('Failed to load content. Please try again later.', contentRows);
        } finally {
            this.hideLoading(contentRows);
        }
    },

    setupMovieCardListeners() {
        document.querySelectorAll('.play-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const movieId = e.currentTarget.dataset.id;
                const event = new CustomEvent('playVideo', { detail: { movieId } });
                document.dispatchEvent(event);
            });
        });

        // add to list button
        document.querySelectorAll('.add-btn').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                const movieId = e.currentTarget.dataset.id;
                try {
                    await api.addToList(movieId);
                    // update UI to show added state
                    e.currentTarget.innerHTML = `
                        <svg class="card__icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" />
                        </svg>
                    `;
                } catch (error) {
                    console.error('Failed to add to list:', error);
                }
            });
        });

        // info button
        document.querySelectorAll('.info-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const movieId = e.currentTarget.dataset.id;
                console.log('Show info for movie:', movieId);
            });
        });
    },

    init() {
        feather.replace();
    }
};

export default ui; 
