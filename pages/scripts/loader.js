class MovieLoader extends Hls.DefaultConfig.loader {
    constructor(config) {
        super(config);
        this.url = config.url;
        this.movie_id = config.movie_id;
    }

    load(context, config, callbacks) {
        const requestData = {
            movie_id: this.movie_id,
            request_type: context.type ? 'manifest' : 'segment',
        };

        if (requestData.request_type === 'segment') {
            requestData.segment_info = {
                sn: context.frag.sn,
                start: context.frag.start
            };
        }

        fetch(this.url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(requestData),
            credentials: 'include'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Request failed: ${response.status} ${response.statusText}`);
            }

            const contentType = response.headers.get('content-type');
            if (contentType === 'application/vnd.apple.mpegurl') {
                return response.text();
            } else if (contentType === 'video/MP2T') {
                return response.arrayBuffer();
            } else {
                throw new Error('Unexpected content type: ' + contentType);
            }
        })
        .then(data => {
            const response = {
                url: context.url,
                data: data
            };

            const stats = {
                aborted: false,
                loaded: data.length,
                total: data.length,
                trequest: performance.now(),
                tfirst: performance.now(),
                tload: performance.now(),
                loading : { start: 0, first: 0, end: 0 },
                parsing : { start: 0, end: 0 },
                buffering : { start: 0, first: 0, end: 0 },
            };

            callbacks.onSuccess(response, stats, context);
        })
        .catch(error => {
            console.error('Loader error:', error);
            callbacks.onError(error, context);
        });
    }
}

function getStreamer(url, movie_id, startPosition = -1) {
    const config = {
        loader: MovieLoader,
        startPosition: startPosition,
        lowLatencyMode: true,
        movie_id: movie_id,
        url: url
    };
    return new Hls(config);
}

export { getStreamer };
