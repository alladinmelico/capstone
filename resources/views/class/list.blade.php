

@push('scripts')
    <script>
        const axios = window.axios
        axios.get('https://classroom.googleapis.com/v1/courses')
        .then(function (response) {
            const classes = response.courses
        })
    </script>
@endpush
