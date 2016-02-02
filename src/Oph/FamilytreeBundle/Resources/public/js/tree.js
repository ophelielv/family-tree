var boxWidth = 150,
    boxHeight = 140,
    nodeWidth = 100,
    nodeHeight = 200,
    // duration of transitions in ms
    duration = 750, 
    // d3 multiplies the node size by this value 
    // to calculate the distance between nodes
    separation = 1.8;//.5
    


/**
 * For the sake of the examples, I want the setup code to be at the top.
 * However, since it uses a class (Tree) which is defined later, I wrap
 * the setup code in a function at call it at the end of the example.
 * Normally you would extract the entire Tree class defintion into a
 * separate file and include it before this script tag.
 */
function setupTree(apiPath, sitePath) {
  // Setup zoom and pan
  var zoom = d3.behavior.zoom()
    .scaleExtent([.1,1])
    .on('zoom', function(){
      svg.attr("transform", "translate(" + d3.event.translate + ") scale(" + d3.event.scale + ")");/*déplacement*/
      svg.attr("transform");
    })
    // Offset so that first pan and zoom does not jump back to the origin
    .translate([400, 200]);
    
  var svg = d3.select("#myDiagram").append("svg")
    .attr('width', '100%')
    .attr('height', '100%')
    .call(zoom)
    .append('g')
    
    // Left padding of tree so that the whole root node is on the screen.
    // TODO: find a better way
    .attr("transform", "translate(400,200)");
  
  
  // One tree to display the ancestors
  var ancestorTree = new Tree(svg, 'ancestor', 1);
  ancestorTree.children(function(person){
    // If the person is collapsed then tell d3
    // that they don't have any ancestors.
    if(person.collapsed){
      return;
    } else {
      return person._parents;
    }
  });
    
  // Use a separate tree to display the descendants
  var descendantsTree = new Tree(svg, 'descendant', -1);
  descendantsTree.children(function(person){
    if(person.collapsed){
      return;
    } else {
      return person._children;
    }
  });
  
  d3.json(apiPath, function(error, json){
    
    if(error) {
      return console.error(error);
    }
  
    // D3 modifies the objects by setting properties such as
    // coordinates, parent, and children. Thus the same node
    // node can't exist in two trees. But we need the root to
    // be in both so we create proxy nodes for the root only.
    var ancestorRoot = rootProxy(json);
    var descendantRoot = rootProxy(json);
    
    // Start with only the first few generations of ancestors showing
    if (ancestorRoot._parents != undefined && ancestorRoot._parents.length > 0){
      ancestorRoot._parents.forEach(function(_parents){
        if ( _parents._parents != undefined){
        _parents._parents.forEach(collapse);
          
        }
      });
    }
    // Start with only one generation of descendants showing
    if(descendantRoot._children != undefined){
      descendantRoot._children.forEach(collapse);
    }
    
    // Set the root nodes
    ancestorTree.data(ancestorRoot);
    descendantsTree.data(descendantRoot);
    
    // Draw the tree
    ancestorTree.draw(ancestorRoot, sitePath);
    descendantsTree.draw(descendantRoot, sitePath);
        
  });

}

function rootProxy(root){
  return {
    name: root.name,
    id: root.id,
    urlImage: root.urlImage,
    altImage: root.altImage,
    x0: 0,
    y0: 0,
    _children: root._children,
    _parents: root._parents,
    collapsed: false
  };
}

/**
 * Shared code for drawing ancestors or descendants.
 * `selector` is a class that will be applied to links
 * and nodes so that they can be queried later when
 * the tree is redrawn.
 * `direction` is either 1 (forward) or -1 (backward).
 */
var Tree = function(svg, selector, direction){
  this.svg = svg;
  this.selector = selector;
  this.direction = direction;
  
  this.tree = d3.layout.tree()
      
      // Using nodeSize we are able to control
      // the separation between nodes. If we used
      // the size parameter instead then d3 would
      // calculate the separation dynamically to fill
      // the available space.
      .nodeSize([nodeWidth, nodeHeight])
      
      // By default, cousins are drawn further apart than siblings.
      // By returning the same value in all cases, we draw cousins
      // the same distance apart as siblings.
      .separation(function(){
        return separation;
      });
};
  
/**
 * Set the `children` function for the tree
 */
Tree.prototype.children = function(fn){
  this.tree.children(fn);
  return this;
};

/**
 * Set the root of the tree
 */
Tree.prototype.data = function(data){
  this.root = data;
  return this;
};
  
/**
 * Draw/redraw the tree
 */
Tree.prototype.draw = function(source, sitePath){
  if(this.root){
    var nodes = this.tree.nodes(this.root),
        links = this.tree.links(nodes);    
    this.drawLinks(links, source);
    this.drawNodes(nodes, source, sitePath);
  } else {
    throw new Error('Missing root');
  }
  return this;
};

/**
 * Draw/redraw the connecting lines
 */
Tree.prototype.drawLinks = function(links, source){
  
  var self = this;
  
  // Update links
  var link = self.svg.selectAll("path.link." + self.selector)
  
      // The function we are passing provides d3 with an id
      // so that it can track when data is being added and removed.
      // This is not necessary if the tree will only be drawn once
      // as in the basic example.
      .data(links, function(d){ return d.target.id; });
  
  // Add new links   
  // Transition new links from the source's
  // old position to the links final position
  link.enter().append("path")
      .attr("class", "link " + self.selector)
      .attr("d", function(d) {
        var o = {x: source.x0, y: self.direction * (source.y0 + boxWidth/2)};
        return transitionElbow({source: o, target: o});
      });
    
  // Update the old links positions
  link.transition()
      .duration(duration)
      .attr("d", function(d){
        return elbow(d, self.direction);
      });
  
  // Remove any links we don't need anymore
  // if part of the tree was collapsed
  // Transition exit links from their current position
  // to the source's new position
  link.exit()
      .transition()
      .duration(duration)
      .attr("d", function(d) {
        var o = {x: source.x, y: self.direction * (source.y + boxWidth/2)};
        return transitionElbow({source: o, target: o});
      })
      .remove();
};

  
/**
 * Draw/redraw the person boxes.
 */
Tree.prototype.drawNodes = function(nodes, source, sitePath){
  
  var self = this;
  
  // Update nodes    
  var node = self.svg.selectAll("g.person." + self.selector)
      
      // The function we are passing provides d3 with an id
      // so that it can track when data is being added and removed.
      // This is not necessary if the tree will only be drawn once
      // as in the basic example.
      .data(nodes, function(person){ return person.id; });
      
  // Add any new nodse
  var nodeEnter = node.enter().append("g")
      .attr("class", "person " + self.selector)
      
      // Add new nodes at the right side of their child's box.
      // They will be transitioned into their proper position.
      .attr('transform', function(person){
        return 'translate(' + (self.direction * (source.y0 + boxWidth/2)) + ',' + source.x0 + ')';
      })
      .on('click', function(person){
        self.togglePerson(person);
      });/////////////////////////////////////////////// LA DIRECTION DE L'ARBRE C'EST PAR LÀ

  // Draw the rectangle person boxes.
  // Start new boxes with 0 size so that
  // we can transition them to their proper size.
  nodeEnter.append("rect")
      .attr({
        x: 0,
        y: 0,
        width: 0,
        height: 0
      });
      

  // Draw the person's name and position it inside the box
  nodeEnter.append("svg:image")
    .attr("xlink:href", function(d){ return "/web/" + d.urlImage; })
    .attr("alt", function(d){return d.altImage;})
    .attr('x',-60)
    .attr('y',-60)
    .attr('width', 120)
    .attr('height', 90)
    .style('fill-opacity', 0);
    
  nodeEnter.append("a")
    .attr("xlink:href",  function(d){ return sitePath + "personne/" + d.id; } /*function(){ return Routing.generate('oph_familytree_homepage'); }*/ )
    .attr("alt", function(d){ return "+ d'informations sur " + d.name; } ) //Routing.generate('oph_familytree_view', { id: d.id })
    .append("text")
      .attr("dx", -65)
      .attr("dy", 50)
      .attr("text-anchor", "start")
      .attr('class', 'name')
      .text(function(d) { 
        return d.name; 
      })
      .style('fill-opacity', 0);
  
  // Update the position of both old and new nodes
  var nodeUpdate = node.transition()
      .duration(duration)
      .attr("transform", function(d) { return "translate(" + (self.direction * d.y) + "," + d.x + ")"; });
      
  // Grow boxes to their proper size    
  nodeUpdate.select('rect')
      .attr({
        x: -(boxWidth/2),
        y: -(boxHeight/2),
        width: boxWidth,
        height: boxHeight
      });
  
  // Move text to it's proper position
  nodeUpdate.select('text')
      .attr("dx", -(boxWidth/2) + 10)
      .style('fill-opacity', 1);
  
  // Remove nodes we aren't showing anymore
  var nodeExit = node.exit()
      .transition()
      .duration(duration)
      
      // Transition exit nodes to the source's position
      .attr("transform", function(d) { return "translate(" + (self.direction * (source.y + boxWidth/2)) + "," + source.x + ")"; })
      /*.attr("transform", function(d) { return "translate(" +  source.x + "," +  (self.direction * (source.y + boxWidth/2))  + ")"; })*/
      .remove();
  
  // Shrink boxes as we remove them    
  nodeExit.select('rect')
      .attr({
        x: 0,
        y: 0,
        width: 0,
        height: 0
      });
      
  // Fade out the text as we remove it
  nodeExit.select('text')
      .style('fill-opacity', 0)
      .attr('dx', 0);
  
  // Stash the old positions for transition.
  nodes.forEach(function(person) {
    person.x0 = person.x;
    person.y0 = person.y;
  });
  
};

/**
 * Update a person's state when they are clicked.
 */
Tree.prototype.togglePerson = function(person){
  
  // Don't allow the root to be collapsed because that's
  // silly (it also makes our life easier)
  if(person === this.root){
    return;
  }
  
  // Non-root nodes
  else {
  
    if(person.collapsed){
      person.collapsed = false;
    } else {
      collapse(person);
    }
    
    this.draw(person);
  }
};

/**
 * Collapse person (hide their ancestors). We recursively
 * collapse the ancestors so that when the person is
 * expanded it will only reveal one generation. If we don't
 * recursively collapse the ancestors then when
 * the person is clicked on again to expand, all ancestors
 * that were previously showing will be shown again.
 * If you want that behavior then just remove the recursion
 * by removing the if block.
 */
function collapse(person){
  person.collapsed = true;
  if(person._parents){
    person._parents.forEach(collapse);
  }
  if(person._children){
    person._children.forEach(collapse);
  }
}
    
/**
 * Custom path function that creates straight connecting
 * lines. Calculate start and end position of links.
 * Instead of drawing to the center of the node,
 * draw to the border of the person profile box.
 * That way drawing order doesn't matter. In other
 * words, if we draw to the center of the node
 * then we have to draw the links first and the
 * draw the boxes on top of them.
 */
function elbow(d, direction) {
  var sourceX = d.source.x,
      sourceY = d.source.y + (boxWidth / 2),
      targetX = d.target.x,
      targetY = d.target.y - (boxWidth / 2);
      
  return "M" + (direction * sourceY) + "," + sourceX
    + "H" + (direction * (sourceY + (targetY-sourceY)/2))
    + "V" + targetX 
    + "H" + (direction * targetY);
}

/**
 * Use a different elbow function for enter
 * and exit nodes. This is necessary because
 * the function above assumes that the nodes
 * are stationary along the x axis.
 */
function transitionElbow(d){
  return "M" + d.source.y + "," + d.source.x
    + "H" + d.source.y
    + "V" + d.source.x 
    + "H" + d.source.y;
}

///////////////////////////////////////////////