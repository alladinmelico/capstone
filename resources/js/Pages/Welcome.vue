<template>
    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
        <div v-if="canLogin" class="fixed top-0 right-0 px-6 py-4 sm:block">
            <inertia-link v-if="$page.props.auth.user" href="/dashboard" class="text-sm text-gray-700 underline">
                Dashboard
            </inertia-link>

            <template v-else>
                <a :href="route('oauth')" class="text-gray-700 underline bg-primary p-2 rounded-lg text-base m-2">
                    Google Sign in
                </a>
            </template>
        </div>

        <div class="mx-auto sm:px-6 lg:px-8 max-w-3xl my-8">
            <div class="p-4 rounded-lg w-1/2 bg-white mx-auto shadow-lg">
                <img src="/ssc-system-banner.jpg" alt="logo">
            </div>
            <h2 class="text-2xl mt-4">Abstract</h2>
            <p class="p-4 mt-4 mx-auto text-justify divide-solid divide-y-4 divide-primary-dark">The unexpected global spread of the COVID-19 pandemic has halted millions of people's lives,
                 including their educational opportunities. Educational institutions implemented online classes to address the situation.
                 Most courses require laboratory and hands-on activities to deliver quality discussion and enhance
                 the skills of the students. Recently, a memorandum was released about the reimplementation of face-to-face classes
                 within selected courses. The Commission on Higher Education (CHED) has given approval to 24 higher education institutions (HEIs)
                 to hold "limited face-to-face classes" with selected allied health-related degree programs,
                 beginning in the second semester of the academic year 2020-2021. “Limited face-to-face classes” have been prioritized at universities
                 that offer these specific degrees. If our university will also be given the chance to conduct a limited face-to-face class,
                  how will our school administration keep the students safe inside the campus?
                  If the CHED’s initial implementation goes well, there's a good chance that limited face-to-face classes
                  will also be implemented in engineering and IT courses.
                <br>
                <br>
                This system's scheduling method could substantially benefit the school's management
                in implementing safe face-to-face classes. The scheduling system will incorporate
                the protocols provided by the IATF. One to those protocols is the restrictions in terms of the number of people
                allowed inside a room. This system will utilize notifications and real-time data onto
                the applications to keep the users updated. It will also take advantage of the TUP email and Google Classroom.
                <br>
                <br>
                The researchers also recognize the importance of integrating body temperature scanning and
                hand sanitization into the system. Since this method automates the procedure,
                security staff may have less contact with most students that access the school.
                 Furthermore, having digital logs of the students’ temperatures could be utilized
                  to produce insights or analytics.
            </p>
            <h2 class="text-2xl mt-4">Our Team</h2>
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-8 text-center">
                <div class="bg-white p-4 rounded-lg hover:shadow-xl">
                    <img src="/espenida.jpg" alt="espenida" class="rounded-lg mb-4 ">
                    <strong class="text-lg">Patricia Mae Espenida</strong><br>
                    <em>Android developer and UI designer</em><br>
                    <a href="#">patriciamae.espenida@tup.edu.ph</a>
                </div>
                <div class="bg-white p-4 rounded-lg hover:shadow-xl">
                    <img src="/melico.jpg" alt="espenida" class="rounded-lg mb-4">
                    <strong class="text-lg">Alladin Melico</strong><br>
                    <em>Project lead developer</em><br>
                    <a href="#">alladin.melico@tup.edu.ph</a>
                </div>
                <div class="bg-white p-4 rounded-lg hover:shadow-xl">
                    <img src="/tongson.jpg" alt="espenida" class="rounded-lg mb-4">
                    <strong class="text-lg">Elaine Tongson</strong><br>
                    <em>Android developer and Project documenter</em><br>
                    <a href="#">elaine.tongson@tup.edu.ph</a>
                </div>
                <div class="bg-white p-4 rounded-lg hover:shadow-xl">
                    <img src="/saba.jpg" alt="espenida" class="rounded-lg mb-4">
                    <strong class="text-lg">Joanna Marie Saba</strong><br>
                    <em>Raspbery Pi application developer</em><br>
                    <a href="#">joannamarie.saba@tup.edu.ph</a>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>

<script>
    export default {
        props: {
            canLogin: Boolean,
            canRegister: Boolean,
            laravelVersion: String,
            phpVersion: String,
        }
    }
</script>
