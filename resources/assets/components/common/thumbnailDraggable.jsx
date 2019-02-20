import React, { Component } from 'react'
import { findDOMNode } from 'react-dom'
import {
	DragSource,
	DropTarget,
} from 'react-dnd'
import flow from 'lodash/flow';

var style= {
  border: "1px solid #ccc",
  borderRadius: 0,
  padding: 10,
  marginBottom: 8,
  width: 50,
  height: 50,
} 

const cardSource = {
	beginDrag(props) {
		return {
			id: props.img,
			index: props.img_index,
		}
	},
}

const cardTarget = {
	hover(props, monitor, component) {
		if (!component) {
			return null
		}
		const dragIndex = monitor.getItem().index
    const hoverIndex = props.img_index

		// Don't replace items with themselves
		if (dragIndex === hoverIndex) {
			return
		}

		// Determine rectangle on screen
		const hoverBoundingRect = (findDOMNode(
			component,
		)).getBoundingClientRect()

		// Get vertical middle
		const hoverMiddleY = (hoverBoundingRect.bottom - hoverBoundingRect.top) / 2

		// Determine mouse position
		const clientOffset = monitor.getClientOffset()

		// Get pixels to the top
		const hoverClientY = (clientOffset).y - hoverBoundingRect.top

		// Only perform the move when the mouse has crossed half of the items height
		// When dragging downwards, only move when the cursor is below 50%
		// When dragging upwards, only move when the cursor is above 50%

		// Dragging downwards
		if (dragIndex < hoverIndex && hoverClientY < hoverMiddleY) {
			return
		}

		// Dragging upwards
		if (dragIndex > hoverIndex && hoverClientY > hoverMiddleY) {
			return
		}

		// Time to actually perform the action
		props.dropThumbnail(dragIndex, hoverIndex);

		// Note: we're mutating the monitor item here!
		// Generally it's better to avoid mutations,
		// but it's good here for the sake of performance
		// to avoid expensive index searches.
		monitor.getItem().index = hoverIndex
	},
}

class ThumbnailDraggable extends Component {
	 render() {
		const {
      img, 
      imageForeground, 
      onClick, 
      isDragging, 
      connectDragSource,
			connectDropTarget,
		} = this.props
		let realStyle = {...style};
    realStyle.opacity = isDragging ? 0 : 1;

		return connectDragSource(
			connectDropTarget(
        <span
        className={
          "c-pointer mx-1 " +
          (img === imageForeground
            ? "thumbnailSpanButtonSelected"
            : "thumbnailSpanButton")
        }
        style={realStyle}
        onClick={onClick}
      >
        <img src={img} className="imageFillDiv" />
      </span>
      ),
		)
	}
}

export default flow(
  DragSource(
		'ThumbnailDraggable',
		cardSource,
		(connect, monitor) => ({
			connectDragSource: connect.dragSource(),
			isDragging: monitor.isDragging(),
		}),
	),
  DropTarget(
    'ThumbnailDraggable',
    cardTarget,
    (connect) => ({
      connectDropTarget: connect.dropTarget(),
    }),
  )
)(ThumbnailDraggable)

/*import React, { Component } from 'react';
import { DragSource } from 'react-dnd';

var style= {
  border: "1px solid #ccc",
  borderRadius: 0,
  padding: 10,
  marginBottom: 8,
  width: 50,
  height: 50,
} 

const itemSource = {
  beginDrag(props){
    return props;
  },
  endDrag(props, monitor, component){
    return props.handleDrop(props);
  }
}

function collect(connect, monitor){
  return {
    connectDragSource: connect.dragSource(),
    connectDragPreview: connect.dragPreview(),
    isDragging: monitor.isDragging(),
  }
}

class ThumbnailDraggable extends Component {
  state = { 
    
  }

  render() { 
    const { img, imageForeground, onClick, isDragging, connectDragSource} = this.props;
    let realStyle = {...style};
    realStyle.opacity = isDragging ? 0 : 1;
    return connectDragSource(
      <span
        className={
          "c-pointer mx-1 " +
          (img === imageForeground
            ? "thumbnailSpanButtonSelected"
            : "thumbnailSpanButton")
        }
        style={realStyle}
        onClick={onClick}
      >
        <img src={img} className="imageFillDiv" />
      </span>
    );
  }
}
 
export default DragSource('item', itemSource, collect)(ThumbnailDraggable);*/