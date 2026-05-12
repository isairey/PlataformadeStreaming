const ContentManager = {
    init() {
        this.setupEventListeners();
    },
    setupEventListeners() {
        const searchIcon = document.querySelector('.nav-item.icon i[data-feather="search"]');
        if (searchIcon) {
            searchIcon.addEventListener('click', () => {
                // TODO: Implement search functionality

                this.handleSearch();
            });
        }
        document.addEventListener('click', (e) => {
            const contentItem = e.target.closest('.content');
            if (contentItem) {
                this.handleContentClick(contentItem);
            }
        });
    },
    handleSearch() {
        console.log('Search functionality to be implemented');
    },

    handleContentClick(contentItem) {
        const img = contentItem.querySelector('img');
        if (img) {
            console.log('Content clicked:', img.alt);
            // TODO content click handling
        }
    },
    async updateFromSource(sourceUrl) {
        try {
            const response = await fetch(sourceUrl);
            const data = await response.json();
            this.contentData = data;
            this.updateFeaturedContent();
            this.updateContentRows();
        } catch (error) {
            console.error('Error updating content:', error);
        }
    }
};

document.addEventListener('DOMContentLoaded', () => {
    ContentManager.init();
});

export default ContentManager; 