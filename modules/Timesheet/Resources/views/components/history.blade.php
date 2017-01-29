<table class="table table-responsive" id="timesheets-table">
    <thead>
        <tr>
            <th><label>Description</label></th>
            <th><label>Project</label></th>
            <th><label>User</label></th>
            <th><label>Duration</label></th>
            <th><label>Time Entry</label></th>
        </tr>
    </thead>
    <tbody>
    <tr v-for="item in data" v-bind:class="item.warning" >
        <td class="table-desc" v-if="!item.line_item" colspan="5">
            <!-- only for headers -->
            <h3>@{{ item.description }}</h3>
        </td>

        <td class="table-desc" v-if="item.line_item">
            <a class="edit" v-bind:href="item.edit_url">
                @{{ item.description }}
                <span>edit</span>
            </a>
            <a v-bind:href="item.copy_url" class="copy"><span>copy</span></a>
        </td>
        <td class="table-project" v-if="item.line_item">
            @{{ item.project.name }}
        </td>
        <td v-if="item.line_item">
            @{{ item.user.name }}
        </td>
        <td class="table-time" v-if="item.line_item">
            @{{ item.date.duration_formatted }}
        </td>
        <td v-if="item.line_item">
            <span class="nowrap">@{{ item.date.start }} - @{{ item.date.end }}</span>
        </td>
    </tr>
    </tbody>
</table>
