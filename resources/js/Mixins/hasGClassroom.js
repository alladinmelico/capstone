export default {
    props: {
		token: {
            type: String,
            required: true
        },
	},
    data() {
        return {
            classes: []
        }
    },

    mounted() {
		this.getClasses()
	},

    methods: {
		async getClasses() {
			const axios = window.axios
			const response = await axios
				.get('https://classroom.googleapis.com/v1/courses', {
					params: {
						access_token: this.token,
					},
                    headers: {
                        'Access-Control-Allow-Origin': '*',
                        Accept: 'application/json',
                        'Access-Control-Allow-Methods':'GET,PUT,POST,DELETE,PATCH,OPTIONS',
                    }
				})
				.catch(function (error) {
					if (error.response.status === 401) {
                        axios.post('/logout')
                        window.location.reload()
					}
				})
			this.classes = response.data.courses
		},
    }
}
