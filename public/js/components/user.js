import {icons} from "../const/icons.js";
import {adminPageRegex, teamPageProjectRegex, teamPageRegex} from "../const/regex.js";
import {getProject} from "../project.js";
import {getAuth} from "../api/user.js";

const currentPath = window.location.pathname;

const isTeamPage = teamPageRegex.test(currentPath);
const idAdminPage = adminPageRegex.test(currentPath);
const matches = currentPath.match(teamPageProjectRegex);
let project_id = null;
if(matches) project_id = matches[0];
const project = await getProject(project_id);
const auth = await getAuth();

export async function createUserItem(user) {
    const userItemSection = document.createElement('section');
    userItemSection.className = 'user-item';
    const userSection = (createUserSection(user));
    const statusSpan = document.createElement('span');
    if (isTeamPage) {

        statusSpan.className = 'status';
        if (project && project.user_id === user.id) {
            statusSpan.className += ' coordinator';
            statusSpan.innerHTML = icons['coordinator'] + 'Coordinator';
        } else {
            statusSpan.className += ' member';
            statusSpan.innerHTML = icons['member'] + 'Member';
        }

    }
    else if(idAdminPage){

        statusSpan.className = 'status';
        if (user.is_admin) {
            statusSpan.className += ' admin';
            statusSpan.innerHTML = icons['admin'] + 'Admin';
        } else {
            statusSpan.className += ' user';
            statusSpan.innerHTML = icons['user'] + 'User';
        }
        userSection.appendChild(statusSpan);
    }
    userItemSection.appendChild(userSection);
    userItemSection.appendChild(statusSpan);
    if (isTeamPage && (project.user_id !== user.id && auth.id === project.user_id)) {
        userItemSection.appendChild(createActionsSection(user, ['promote', 'remove']));
    } else if (idAdminPage && auth.is_admin) {
        userItemSection.appendChild(createActionsSection(user, ['edit', 'block', 'delete']));
    }
    return userItemSection;
}

function createUserCard(user) {
    const userCard = document.createElement('section');
    userCard.classList.add('userCard');
    userCard.innerHTML = ` <img class="icon avatar" src="/img/default_user.png" alt="default user icon">
                          <section class="info">
                              <h3>${user.name}</h3>
                              <h5>${user.email}</h5>
                          </section>`;
    return userCard;
}

function createUserSection(user) {
    const userSection = document.createElement('section');
    userSection.className = 'userSection';

    const profileLink = document.createElement('a');
    profileLink.href = "/user-profile/" + user.id; // Replace with the actual URL

    profileLink.appendChild(createUserCard(user));
    userSection.appendChild(profileLink);
    return userSection;
}

function createActionsSection(user, actions) {
    const actionsSection = document.createElement('section');
    actionsSection.className = 'actions';
    for (let action of actions) {
        let span;
        if(action ==='edit'){
            span = document.createElement('a');
            span.href = "/user-profile/"+user.id+"/edit";
        }else if(action === 'delete'){
            span = document.createElement('button');
            span.for= user.id+"-"+action;

            span.classList.add('invisible');
            const form = document.querySelector('#delete');
            span.setAttribute('form', form.id);
            span.setAttribute('formAction', '/user/' + user.id + '/' + action);

        }
        else{
            span = document.createElement('span');
            span.classList.add(action);
        }

        span.classList.add( action);
        span.id = user.id;
        span.innerHTML = icons['user-' + action];
        actionsSection.appendChild(span);
    }
    return actionsSection;
}


