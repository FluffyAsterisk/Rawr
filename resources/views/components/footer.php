        </main>
    </div>

</body>
<script>
    let form = "";

    function showCreateTicket()
    {
        let html = `
            <section class="createTicketForm fullscreen_form">
                <div class="container | pd-32 card">
                    <h2 class="text-center mbtm-20">New Ticket</h2>
                    <p class="mbtm-20">All fields are required!</p>
                    <form action="нахуй" method="POST">
                        <input type="text" name="name" placeholder="Enter name here...">
                        <input type="email" name="email" placeholder="Enter email here...">
                        <input type="text" name="title" placeholder="Enter title for the ticket here, keep it short...">

                        <input list="select-client" name="client" placeholder="Select client...">
                        <datalist id="select-client">
                            <option value="admin">admin</option>
                            <option value="oleg">Oleg</option>
                        </datalist>

                        <input list="select-engineer" name="client" placeholder="Select engineer...">
                        <datalist id="select-engineer">
                            <option value="admin">admin</option>
                            <option value="oleg">Oleg</option>
                        </datalist>

                        <textarea name="comment" placeholder="Enter extra details here..."></textarea>
                        <div class="priority_buttons | mtp-8 mbtm-8">
                            <!-- <button>Low</button>
                            <button>Normal</button>
                            <button>High</button> -->
                            <input type="radio" name="priority" value="low" id="low">
                            <label for="low">Low</label>

                            <input type="radio" name="priority" value="medium" id="medium" checked>
                            <label for="medium">Medium</label>

                            <input type="radio" name="priority" value="high" id="high">
                            <label for="high">High</label>
                        </div>

                        <div class="cancel_create">
                            <button onclick="closeTicketForm()" id="cancelButton">Cancel</button>
                            <button>Create Ticket</button>
                        </div>
                    </form>
                </div>
            </section>
        `;

        document.querySelector('body').insertAdjacentHTML('beforeend', html);

        document.getElementById('cancelButton').addEventListener('click', e => {
            e.preventDefault();
        });

        form = document.querySelector('.createTicketForm');
    }

    function closeTicketForm()
    {
        if ( form != "" )
        {
            form.remove();
        }
    }
</script>
</html>
