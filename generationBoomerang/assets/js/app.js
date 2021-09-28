/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';
import React from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

const TodoListElement = document.querySelector('span.react-like');
const getData = (name, json = true) => {
    const value = TodoListElement.getAttribute(`data-${name}`);
    return json ? JSON.parse(value) : value;
};

class LikeButton extends React.Component{
    
    state = {
        likes: this.props.getData(items) || 0,
        isLiked: this.props.isLiked || false
    };
    
    handleClick = () => {
        axios.get("article/"+307+"/like")
            .then(res => {
            const isLiked = res.data.isLiked;
            const likes = res.data.likes;
            this.setState({likes, isLiked});
        })
    }
    
    render(){
        return (
            <button className="btn btn-like" onClick={this.handleClick}>
                {this.state.likes}&nbsp;
                <i className={this.state.isLiked ? "fas fa-thumbs-up" : "far fa-thumbs-down"}/> 
                {this.state.isLiked ? "Je n'aime plus !" : "J'aime"}
            </button>
        );
    }
}

document.querySelectorAll("span.react-like").forEach(function(span){
    ReactDOM.render(React.createElement(LikeButton), span);
})