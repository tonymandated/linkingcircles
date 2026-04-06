<x-layouts.marketing title="Contact" description="Contact Linking Circles Academy.">
    <section class="lc-container py-16">
        <h1 class="lc-section-title">Contact</h1>
        <p class="mt-6 max-w-3xl text-base leading-8">
            Questions about admissions, programs, or partnerships? Reach out and our team will respond.
        </p>

        <div class="mt-10 grid gap-8 md:grid-cols-2">
            <article class="lc-card">
                <h2 class="text-lg font-semibold text-brand-heading">Get in Touch</h2>
                <p class="mt-3 text-sm leading-7">admin@linkingcirclesacademy.com</p>
                <p class="mt-1 text-sm leading-7">+234 812 824 5532</p>
                <p class="mt-1 text-sm leading-7">21B Ahmadu Bello Way, Kaduna, Nigeria</p>
            </article>

            <form class="lc-card" aria-describedby="contact-help">
                <p id="contact-help" class="mb-4 text-sm text-brand-body">This starter clone includes the UI only. Submit handling can be wired next.</p>

                <label for="name" class="block text-sm font-medium text-brand-heading">Name</label>
                <input id="name" name="name" type="text" class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm" autocomplete="name">

                <label for="email" class="mt-4 block text-sm font-medium text-brand-heading">Email</label>
                <input id="email" name="email" type="email" class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm" autocomplete="email">

                <label for="message" class="mt-4 block text-sm font-medium text-brand-heading">Message</label>
                <textarea id="message" name="message" rows="5" class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm"></textarea>

                <button type="button" class="mt-4 rounded-md bg-brand-primary px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700">
                    Send Message
                </button>
            </form>
        </div>
    </section>
</x-layouts.marketing>
