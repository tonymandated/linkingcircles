<x-layouts.marketing title="Home" description="Serving Academic Excellence to African Students Globally.">
    <section class="bg-white">
        <div class="lc-container grid gap-10 py-16 md:grid-cols-2 md:items-center">
            <div>
                <p class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-brand-primary">
                    Endorsed by Education Partners
                </p>
                <h1 class="mt-4 font-serif text-4xl font-semibold leading-tight text-brand-heading md:text-6xl">
                    Serving Academic Excellence to African Students Globally
                </h1>
                <p class="mt-5 max-w-2xl text-lg leading-8 text-brand-body">
                    Linking Circles Academy provides high-quality, accessible education pathways that
                    bridge academic opportunity, professional growth, and cultural relevance.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('programs') }}" wire:navigate class="rounded-md bg-brand-primary px-5 py-3 text-sm font-semibold text-white hover:bg-sky-700">
                        Explore Programs
                    </a>
                    <a href="{{ route('contact') }}" wire:navigate class="rounded-md border border-zinc-300 px-5 py-3 text-sm font-semibold text-brand-heading hover:bg-zinc-50">
                        Contact Us
                    </a>
                </div>
            </div>

            <div class="lc-card">
                <img
                    src="{{ asset('img/linking-circles-logo-1024x713.jpg') }}"
                    alt="Linking Circles Academy logo"
                    class="mx-auto h-auto w-full max-w-sm rounded-lg"
                >
            </div>
        </div>
    </section>

    <section class="lc-container py-16">
        <h2 class="lc-section-title">What We Do</h2>
        <div class="mt-8 grid gap-6 md:grid-cols-3">
            <article class="lc-card">
                <h3 class="font-serif text-2xl font-semibold text-brand-heading">Virtual Learning</h3>
                <p class="mt-3 text-sm leading-7">
                    Flexible online programs that support learners at home and across borders through
                    structured, guided instruction.
                </p>
            </article>
            <article class="lc-card">
                <h3 class="font-serif text-2xl font-semibold text-brand-heading">Teacher Training</h3>
                <p class="mt-3 text-sm leading-7">
                    Practical and inclusive training programs for teachers who want to improve outcomes
                    for diverse classrooms.
                </p>
            </article>
            <article class="lc-card">
                <h3 class="font-serif text-2xl font-semibold text-brand-heading">Cultural Heritage Programs</h3>
                <p class="mt-3 text-sm leading-7">
                    Learner-centered programs that preserve and celebrate African culture while building
                    global academic skills.
                </p>
            </article>
        </div>
    </section>

    <section class="bg-white py-16">
        <div class="lc-container">
            <h2 class="lc-section-title">Why Choose Linking Circles Academy?</h2>
            <div class="mt-8 grid gap-6 md:grid-cols-2">
                <article class="lc-card">
                    <p class="font-semibold text-brand-heading">Accessible and Inclusive Delivery</p>
                    <p class="mt-2 text-sm leading-7">Programs are designed to be clear, supportive, and accessible for diverse learners.</p>
                </article>
                <article class="lc-card">
                    <p class="font-semibold text-brand-heading">Trusted Learning Community</p>
                    <p class="mt-2 text-sm leading-7">Students, teachers, and families join a network that values growth and mentorship.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="lc-container py-16">
        <h2 class="lc-section-title">Start Your Educational Journey Now</h2>
        <p class="mt-4 max-w-3xl text-base leading-8">
            Ready to begin? Explore our available programs or contact our admissions team for personalized guidance.
        </p>
        <a href="{{ route('programs') }}" wire:navigate class="mt-6 inline-flex rounded-md bg-brand-primary px-5 py-3 text-sm font-semibold text-white hover:bg-sky-700">
            Get Started Now
        </a>
    </section>
</x-layouts.marketing>
