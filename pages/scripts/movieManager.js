const MovieManager = {
    async fetchMovies(category) {
        try {
            const token = localStorage.getItem('token');
            if (!token) {
                throw new Error('No authentication token found');
            }

            const response = await fetch('/api/home.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            });


            if (!response.ok) {
                throw new Error('Failed to fetch movies');
            }

            const data = await response.json();
            if (!data.ok) {
                throw new Error(data.message || 'Failed to fetch movies');
            }

            if (category) {
                return data.categories.find(cat => cat.title === category)?.movies || [];
            }
            return data.categories.flatMap(cat => cat.movies);
        } catch (error) {
            console.error('Error fetching movies:', error);
            return [];
        }
    },

    createMovieElement(movie) {
        const content = document.createElement('div');
        content.className = 'content';
        
        const frontCard = document.createElement('div');
        frontCard.className = 'wrapper__front';
        const frontImg = document.createElement('img');
        frontImg.src = movie.thumbnail;
        frontImg.alt = movie.title;
        frontCard.appendChild(frontImg);
        
        const backCard = document.createElement('div');
        backCard.className = 'wrapper__back';
        
        const backHeader = document.createElement('div');
        backHeader.className = 'card__header';
        const backImg = document.createElement('img');
        backImg.src = movie.thumbnail;
        backImg.alt = movie.title;
        backHeader.appendChild(backImg);
        
        const cardBody = document.createElement('div');
        cardBody.className = 'card__body';
        
        const buttonsContainer = document.createElement('div');
        buttonsContainer.className = 'flex justify-between items-center';
        
        const leftButtons = document.createElement('div');
        
        const playButton = document.createElement('button');
        playButton.className = 'btn btn--transparent btn--circle';
        playButton.innerHTML = `
            <svg class="card__icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path d="M21.44 10.72L5.96 2.98A1.38 1.38 0 004 4.213v15.474a1.373 1.373 0 002 1.233l15.44-7.74a1.38 1.38 0 000-2.467v.007z" />
            </svg>
        `;
        
        const addButton = document.createElement('button');
        addButton.className = 'btn btn--transparent btn--circle';
        addButton.innerHTML = `
            <svg class="card__icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path d="M12 0a1.5 1.5 0 011.5 1.5v9h9a1.5 1.5 0 110 3h-9v9a1.5 1.5 0 11-3 0v-9h-9a1.5 1.5 0 110-3h9v-9A1.5 1.5 0 0112 0z" />
            </svg>
        `;
        
        leftButtons.appendChild(playButton);
        leftButtons.appendChild(addButton);
        
        const moreInfoButton = document.createElement('button');
        moreInfoButton.className = 'btn btn--transparent btn--circle';
        moreInfoButton.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="card__icon" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M2.469 6.969a.75.75 0 011.062 0L12 15.439l8.469-8.47a.75.75 0 111.062 1.062l-9 9a.75.75 0 01-1.062 0l-9-9a.75.75 0 010-1.062z" clip-rule="evenodd" />
            </svg>
        `;
        
        buttonsContainer.appendChild(leftButtons);
        buttonsContainer.appendChild(moreInfoButton);
        
        const titleElement = document.createElement('p');
        titleElement.className = 'card__title text';
        titleElement.innerHTML = `
            <span class="text--bold">${movie.type === 'series' ? 'S1:E1' : ''}</span>
            ${movie.title}
        `;
        
        const progressContainer = document.createElement('div');
        progressContainer.className = 'card__progress flex justify-between items-center';
        progressContainer.innerHTML = `
            <div class="progressbar">
                <div class="progressbar__status"></div>
            </div>
            <span class="text text--bold text--muted">51 of 52m</span>
        `;
        
        cardBody.appendChild(buttonsContainer);
        cardBody.appendChild(titleElement);
        cardBody.appendChild(progressContainer);
        
        backCard.appendChild(backHeader);
        backCard.appendChild(cardBody);
        
        content.appendChild(frontCard);
        content.appendChild(backCard);
        
        content.addEventListener('click', (e) => {
            if (!e.target.closest('.btn')) {
                this.handleMovieClick(movie.movie_id);
            }
        });
        
        return content;
    },

    async handleMovieClick(movieId) {
        try {
            const token = localStorage.getItem('token');
            if (!token) {
                throw new Error('No authentication token found');
            }

            const response = await fetch('/api/movie.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(movieId)
            });

            if (!response.ok) {
                throw new Error('Failed to fetch movie details');
            }

            const data = await response.json();
            if (!data.ok) {
                throw new Error(data.message || 'Failed to fetch movie details');
            }

            // TODO: Handle movie details display
            console.log('Movie details:', data.movie);
        } catch (error) {
            console.error('Error fetching movie details:', error);
        }
    },

    async loadMoviesIntoRow(rowElement, category) {
        const slider = rowElement.querySelector('.slider');
        if (!slider) return;

        slider.innerHTML = '';
        
        const loadingIndicator = document.createElement('div');
        loadingIndicator.className = 'loading-indicator';
        loadingIndicator.textContent = 'Loading...';
        slider.appendChild(loadingIndicator);

        try {
            const movies = await this.fetchMovies(category);
            
            slider.removeChild(loadingIndicator);
            if (movies.length === 0) {
                slider.innerHTML = '<div class="error-message">No movies found</div>';
                return;
            }
            
            movies.forEach(movie => {
                const movieElement = this.createMovieElement(movie);
                slider.appendChild(movieElement);
            });
        } catch (error) {
            console.error('Error loading movies:', error);
            slider.innerHTML = '<div class="error-message">Failed to load movies</div>';
        }
    },

    init() {
        const token = localStorage.getItem('token');
        if (!token) {
            console.error('No authentication token found');
            return;
        }
        document.querySelectorAll('.content-row').forEach(async (row) => {
            const title = row.querySelector('.title').textContent;
            await this.loadMoviesIntoRow(row, title);
        });
    }
};

export default MovieManager; 