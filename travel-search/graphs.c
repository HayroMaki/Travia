#include <stdlib.h>
#include <stdio.h>
#include "cJSON.h"
#include <string.h>
#include <dirent.h>
#include <fcntl.h>

#define None (-1)
#define Default 33000

typedef int Element;
typedef double Key;

// Chained List definition and functions :

typedef struct l_cell{
    Element elem;
    struct l_cell* succ;
} L_Cell;

typedef L_Cell* List;

List cons_list(List queue, Element elem) {
    List l = malloc(sizeof(L_Cell));
    l->elem = elem;
    l->succ = queue;
    return l;
}

int len_list(List l) {
    if (l==NULL) {
        return 0;
    } else
    {
        return 1 +len_list(l -> succ);
    }
}

void print_list(List l) {
    if (l==NULL) {
        printf("\n");
    } else {
        printf("%d\t", l->elem);
        print_list(l->succ);
    }
}

void print_good_list(List l, int i, char* buf) {
	if (i == 1) buf[0] = '\0';
    if (l==NULL) {
        if (i == 0) strcat(buf, "]");
        else strcat(buf,"[]");
    } else {
		char tmp[100];
        if (i == 1) {
			sprintf(tmp, "[%d", l->elem+1);
		} else{
			sprintf(tmp,",%d", l->elem+1);
		}
		strcat(buf,tmp);
        print_good_list(l->succ,0,buf);
    }
}

Element ithElement_list(int i, List l, int len) {
    if (i<0 || i>len) exit(EXIT_FAILURE);
    if (i==0) return l->elem;
    else return ithElement_list(i-1, l->succ, len);
}

int contains_list(Element e, List l) {
    if (l->elem == e) return 1;
    if (l->succ==NULL) return 0;
    return contains_list(e, l->succ);
}

void push_list(List* l, Element x) {
    (*l) = cons_list((*l),x);
}

void free_list(List l) {
    while (l != NULL) {
        List temp = l;
        l = l->succ;
        free(temp);
    }
}

// TwinHeap definition and functions :

typedef struct {
    Key key;
    int indexInHeap;
} Data;

typedef struct {
    int* len;
    int* heap;
    Data* data;
} TwinHeap;

TwinHeap newTwinHeap(int len, Key defaultKey) { 
    TwinHeap th;
    th.len = (int *)malloc(sizeof(int));
    *(th.len) = len;
    th.heap = (int *)malloc(len * sizeof(int));
    th.data = (Data *)malloc(len * sizeof(Data));

    for (int i = 0; i < len; i++) {
        th.heap[i] = i;
        th.data[i].key = defaultKey;
        th.data[i].indexInHeap = i;
    }

    return th;
}

int existsInHeap(TwinHeap th, int i) {
    if (i >= *th.len) return 0;
    if (th.heap[th.data[i].indexInHeap] == None) return 0;
    return 1;
}

int emptyHeap(TwinHeap th) {
    return th.heap[0] == None;
}

int parent(int i) { 
    if (i==0)
        return -1;
    return (i-1)/2; 
}

int lChild(int i) { 
    return 2*i + 1; 
}

int rChild(int i) { 
    return 2*i + 2; 
}

double key(TwinHeap th, int heapIndex) {
    return th.data[th.heap[heapIndex]].key;
}

void printNiceHeap_aux(TwinHeap th, int index, int depth) {
    if (!existsInHeap(th, index)) return;
    printf("     %d         ", index);
    printf("%*s", depth, "");
    printf("%d [key=%lf]\n", th.heap[index], key(th, index));
    printNiceHeap_aux(th, lChild(index), depth+1);
    printNiceHeap_aux(th, rChild(index), depth+1);    
}

void printNiceHeap(TwinHeap th) {
    printf("heapIndex  dataIndex\n");
    printNiceHeap_aux(th, 0, 0);
}

void swap(TwinHeap th, int i, int j) {
    int temp = th.heap[i];
    th.heap[i] = th.heap[j];
    th.heap[j] = temp;

    th.data[th.heap[i]].indexInHeap = i;
    th.data[th.heap[j]].indexInHeap = j;
}

void cleanUp(TwinHeap th, int i) {
    if (i > 0) {
        int parentIndex = parent(i);
        
        if (key(th, i) < th.data[th.heap[parentIndex]].key) {
            swap(th, i, parentIndex);
            cleanUp(th, parentIndex);
        }
    }
}

void cleanDown(TwinHeap th, int i) {
    if (i < *(th.len) - 1) {
        int left = lChild(i);
        int right = rChild(i);
        if (existsInHeap(th, left) && existsInHeap(th, right)) {
            int smallest = key(th, left) < key(th, right) ? left : right;
            if (key(th, smallest) < key(th, i)) {
                swap(th, smallest, i);
                cleanDown(th, smallest);
            }
        } else if (existsInHeap(th, left)) {
            if (key(th, left) < key(th, i)) {
                swap(th, left, i);
                cleanDown(th, left);
            }
        } else if (existsInHeap(th, right)) {
            if (key(th, right) < key(th, i)) {
                swap(th, right, i);
                cleanDown(th, right);
            }
        } 
    }
}

void editKey(TwinHeap th , int di, Key newKey) {
    th.data[di].key = newKey;
    cleanUp(th, th.data[di].indexInHeap);
}

void removeNode(TwinHeap th, int i) {
    int lastIndex = *(th.len) - 1;

    th.heap[i] = th.heap[lastIndex];
    th.data[th.heap[i]].indexInHeap = lastIndex;
    th.heap[lastIndex] = None;

    *(th.len) -= 1;

    cleanDown(th,i);
}

int popMinimum(TwinHeap th) {
    int i = th.heap[0];
    removeNode(th, 0);
    return i;
}

int popMinimum_len(TwinHeap th, int len) {
    int new_len = *th.len -1;
    *th.len = len;

    int i = th.heap[0];
    removeNode(th, 0);

    *th.len = new_len;
    return i;
}

void free_TwinHeap(TwinHeap twinHeap) {
    free(twinHeap.len);
    free(twinHeap.heap);
    free(twinHeap.data);
}

// Graph definition and functions :

typedef struct g_cell{
    Element elem;           // Element id/value
    double dist;            // Distance to point of arrival (0 if itself)
    struct g_cell* succ;
    int start;              // Starting point of vertexs in Vertex list
    int amount;             // Number of vertexs in Vertex list
} G_Cell;

typedef G_Cell* Graph_List;

Graph_List cons_graph(Graph_List queue, Element elem, double dist, int start, int amount) {
    Graph_List l = malloc(sizeof(G_Cell));
    l->elem = elem;
    l->dist = dist;
    l->succ = queue;        
    l->start = start;
    l->amount = amount;
    return l;
}

int len_graph_list(Graph_List l) {
    if (l==NULL) {
        return 0;
    } else {
        return 1 + len_graph_list(l -> succ);
    }
}

int contains_graph(Graph_List l, Element e) {
    if (l->elem == e) return 1;
    if (l->succ==NULL) return 0;
    return contains_graph(l->succ, e);
}

G_Cell ithElement_graph(Graph_List l, int i, int len) {
    if (i<0 || i>len) exit(EXIT_FAILURE);
    if (i==0) return *l;
    else return ithElement_graph(l->succ, i-1, len);
}

void print_graph(Graph_List l) {
    if (l==NULL) {
        printf("\n");
    } else {
        printf("%d:(%d,%d):d=%f\n",l->elem,l->start,l->amount,l->dist);
        print_graph(l->succ);
    }
}

void free_Graph(Graph_List graph) {
    G_Cell* current = graph;
    G_Cell* next;

    while (current != NULL) {
        next = current->succ;
        free(current);
        current = next;
    }
}

Graph_List exemple_graph() {
    Graph_List g = NULL;
    g = cons_graph(g,5,0,8,2);
    g = cons_graph(g,4,2.5,7,1);
    g = cons_graph(g,3,3,6,1);
    g = cons_graph(g,2,5.2,4,2);
    g = cons_graph(g,1,8,2,2);
    g = cons_graph(g,0,10,0,2);
    return g;
}

// Vertex list definition and functions :

typedef struct vertex_value{
    int dep;
    int ariv;
    double cost;
} Vertex_value;

typedef Vertex_value* Vertex;

void print_vertex(Vertex* vex, int size) {
    for (size_t i = 0; i < size; i++) {
        printf("%d -> %d = %lf\n",vex[i]->dep,vex[i]->ariv,vex[i]->cost);
    }
    
}

void free_Vertex(Vertex* vex, size_t size) {
    if (vex != NULL) {
        for (size_t i = 0; i < size; i++) {
            free(*&(vex[i]));
        }
        free(vex);
    }
}

Vertex* exemple_vex() {
    Vertex *vex = (Vertex *)malloc(10 * sizeof(Vertex));
    for (int i = 0; i < 10; i++) {
        vex[i] = (Vertex)malloc(sizeof(Vertex_value));
    }
    //0->3 = 6
    vex[0]->dep = 0;
    vex[0]->ariv = 3;
    vex[0]->cost = 6;
    //0->5 = 2
    vex[1]->dep = 0;
    vex[1]->ariv = 5;
    vex[1]->cost = 2;
    //1->0 = 3
    vex[2]->dep = 1;
    vex[2]->ariv = 0;
    vex[2]->cost = 3;
    //1->2 = 1
    vex[3]->dep = 1;
    vex[3]->ariv = 2;
    vex[3]->cost = 1;
    //2->0 = 1
    vex[4]->dep = 2;
    vex[4]->ariv = 0;
    vex[4]->cost = 1;
    //2->4 = 2
    vex[5]->dep = 2;
    vex[5]->ariv = 4;
    vex[5]->cost = 5;
    //3->0 = 3
    vex[6]->dep = 3;
    vex[6]->ariv = 0;
    vex[6]->cost = 3;
    //4->3 = 2
    vex[7]->dep = 4;
    vex[7]->ariv = 3;
    vex[7]->cost = 2;
    //5->2 = 1
    vex[8]->dep = 5;
    vex[8]->ariv = 2;
    vex[8]->cost = 1;
    //5->4 = 1
    vex[9]->dep = 5;
    vex[9]->ariv = 4;
    vex[9]->cost = 1;

    return vex;
}

//JSON reader structures and functions :

typedef struct data_list {
    Graph_List graph;
    Vertex vex;
    int vex_len;
    int dep;
    int arr;
} Data_list;

//Function to extract content from a Json file.
char* read_file(const char* filename) {
    //Open the file :
    FILE* file = fopen(filename, "r");
    if (file < 0) {
        perror("Error opening file ");
    }

    //To get the length of the file
    //Go to the end :
    fseek(file, 0, SEEK_END);
    //Get the length from the end :
    long length = ftell(file);
    //return to the begenning :
    fseek(file, 0, SEEK_SET);

    //Allocate memory for the content :
    char* content = (char*)malloc(length + 1);

    //Read the data file :
    fread(content, 1, length, file);
    //To be sure that the content is finish :
    content[length] = '\0';

    fclose(file);
    return content;
}

//Function to use the content extract from the Json and put them into a graph_list.
Graph_List json_to_graphList(const char* json_str) {

    //Parse the json_str (content extract from a Json file) :
    cJSON* data = cJSON_Parse(json_str);
    if (!data) perror("Error parsing json ");

    //First cell :
    G_Cell* graphList = NULL;

    //Cell create from data :
    cJSON* item;
    cJSON_ArrayForEach(item, data) {
        cJSON* id = cJSON_GetObjectItem(item, "id");
        cJSON* distance = cJSON_GetObjectItem(item, "distance");
        cJSON* start = cJSON_GetObjectItem(item, "start");
        cJSON* amount = cJSON_GetObjectItem(item, "amount");

        if (!cJSON_IsNumber(id) || !cJSON_IsNumber(distance) || !cJSON_IsNumber(start) || !cJSON_IsNumber(amount)) {
            perror("Json not valid ");
            cJSON_Delete(data);
            return NULL;
        }

        //Add the cell tho the list :
        graphList = cons_graph(graphList, id->valueint, distance->valuedouble, start->valueint, amount->valueint);
    }

    cJSON_Delete(data);
    return graphList;
}

//Function to count how many items the Json has.
int count_data(const char* json_str) {
    cJSON* root = cJSON_Parse(json_str);

    if (!root) {
        perror("Error parsing Json ");
        return -1;
    }

    //Get the number of object :
    int count = cJSON_GetArraySize(root);
    cJSON_Delete(root);
    return count;
}

//function to extract data into a vertex list.
Vertex_value* json_to_vertexList(const char* json_str, int num_vertices) {
    cJSON* root = cJSON_Parse(json_str);

    if (!root) {
        perror("Error parsing Json ");
        return NULL;
    }

    int count = 0;
    if (num_vertices) {
        count = num_vertices;
    } else {
        count = count_data(json_str);
    }

    Vertex_value* vertices = malloc(count * sizeof(Vertex_value));
    if (!vertices) {
        fprintf(stderr, "Erreur d'allocation memoire\n");
        cJSON_Delete(root);
        return NULL;
    }

    for (int i = 0; i < count; i++) {
        cJSON* item = cJSON_GetArrayItem(root, i);
        cJSON* id_start = cJSON_GetObjectItem(item, "id_start");
        cJSON* id_end = cJSON_GetObjectItem(item, "id_end");
        cJSON* cost = cJSON_GetObjectItem(item, "cost");

        if (!cJSON_IsNumber(id_start) || !cJSON_IsNumber(id_end) || !cJSON_IsNumber(cost)) {
            fprintf(stderr, "JSON invalide : un champ est manquant ou incorrect\n");
            free(vertices);
            cJSON_Delete(root);
            return NULL;
        }

        vertices[i].dep = id_start->valueint;
        vertices[i].ariv = id_end->valueint;
        vertices[i].cost = cost->valuedouble;
    }

    cJSON_Delete(root);
    return vertices;
}

void print_vertices(Vertex_value* vertices, int num_vertices) {
    for (int i = 0; i < num_vertices; i++) {
        printf("Departure: %d, End: %d, Cost: %.2f\n",
               vertices[i].dep, vertices[i].ariv, vertices[i].cost);
    }
}

//function to get the departure and arrival planets id.
int* get_start_end(char* json_str) {
    cJSON* root = cJSON_Parse(json_str);
    if (!root) {
        perror("Error parsing json ");
        return NULL;
    }

    int* array = malloc(sizeof(int) * 2);
    int start = 0;
    int end = 0;

    //there are only 2 data (one for start, one for end) :
    for (int i = 0; i < 2; i++) {
        cJSON* item = cJSON_GetArrayItem(root, i);
        cJSON* id = cJSON_GetObjectItem(item, "id");

        if(i == 0){start = id->valueint;}
        if(i == 1){end = id->valueint;}
    }

    array[0] = start;
    array[1] = end;
    return array;

}

Graph_List mirrorGraphAux(Graph_List A,Graph_List B) {
    if (A==NULL) {
        return B;
    } else {
        return mirrorGraphAux(A->succ, cons_graph(B,A->elem,A->dist,A->start,A->amount));
    }
}
Graph_List mirrorGraph(Graph_List g) {
    if (g==NULL) {
        return NULL;
    }
    return mirrorGraphAux(g,NULL);
}

//
Data_list GetData(const char* graph_fn, const char* vertex_fn, const char* dep_arr_fn) {
    const char* filename = graph_fn;
    char* json_str = read_file(filename);

    if (json_str == "") {
        perror("Error reading json file : ");
    }

    Graph_List graph = json_to_graphList(json_str);
    free(json_str);

    if (graph) {
        printf("Graph List successfully created:\n");
    } else {
        printf("Error during the creation\n");
        _Exit(EXIT_FAILURE);
    }

    filename = vertex_fn;
    json_str = read_file(filename);

    if (json_str == "") {
        perror("Error reading json file : ");
    }

    int nbData = count_data(json_str);
    Vertex_value* vex = json_to_vertexList(json_str, nbData);
    free(json_str);

    if (vex) {
        printf("Vertex List successfully created:\n");
    } else {
        printf("Error during the creation\n");
        _Exit(EXIT_FAILURE);
    }

    filename = dep_arr_fn;
    json_str = read_file(filename);
    if (json_str == "") {
        perror("Error reading json file : ");
    }
    int* array = get_start_end(json_str);

    //Put every information into a struct data_list :
    Data_list data;

    data.graph = mirrorGraph(graph);
    data.vex = vex;
    data.dep = array[0];
    data.arr = array[1];
    data.vex_len = nbData;
    return data;
}

// Global Functions :

//Function that generate a file with the found path.
void createFile(List path, Element dep, Element arr, char* cost, char* filter) {
	//Create the file
	char filename[50];
	sprintf(filename, "cache_%d-%d_%s_%s.txt",dep+1,arr+1,cost,filter);
	int fd = open(filename,O_WRONLY | O_CREAT | O_TRUNC, 00664);

    //If there's an error
    if (fd < 0) {
        perror("Archive open failed ");
        return;
    }

	//Write into the file
	int buf_size = 512;
	char buf[buf_size];
	print_good_list(path,1,buf);
	printf("%s\n",buf);
	ssize_t bytes_written = write(fd, buf, strlen(buf));
    if (bytes_written == -1) {
        perror("Error writing to file ");
        close(fd);
        return;
    }

    if (close(fd) == -1) {
        perror("Error closing file ");
        return;
    }
}

List traceBack(const Element *f, const Element d, const Element a) {
    List path = NULL;
    Element current =  a;
    do {
        path = cons_list(path, current);
        current = f[current];
    } while (current != d);
    path = cons_list(path, current);
    return path;
}

List a_star(const Graph_List g, Vertex_value* vex, const Element d, const Element a) {
    if (g == NULL) return NULL;

    // Check that departure and arrival planets are in the graph :
    if (!contains_graph(g,d) || !contains_graph(g,a)) {
        printf("Departure or Arrival not in graph.");
        exit(EXIT_FAILURE);
    }

    // If departure and arrival are the same :
    if (d == a) return cons_list(NULL,a);

    /*-------Initialisation--------*/
    // Get lenght :
    int len = len_graph_list(g);

    // Open list as a Twinheap :
    TwinHeap openl = newTwinHeap(len,Default);
    editKey(openl, d, 0);
    *openl.len = 1;

    // Closed list as a chained list :
    List closel = NULL;

    // Father List for traceback :
    Element *f = malloc(len * sizeof(Element));
    f[d] = d;

    while (!emptyHeap(openl) && *openl.len != 0) {
        //printNiceHeap(openl);
        // Take known cell with the minimal estimated total cost (known + estimated).
        // Starts with the departure cell as he's alone in the open list.
        int m_index = popMinimum_len(openl,len);
        len--;
        double m_cost = openl.data[m_index].key;
        G_Cell m = ithElement_graph(g,m_index, len_graph_list(g));

        // Add the current cell to the closed list :
        push_list(&closel,m_index);

        //printf("Checking for %d:%lf :\n",m_index,m_cost);
        //printf("id : %d, start : %d, amount : %d\n",m.elem,m.start,m.amount);
        // Check every vertex coming from the selected cell (m) :
        for (int i = m.start; i < (m.start + m.amount); i++) {
            // Get arrival cell :
            int v_a_id = vex[i].ariv;

            // If this is arrival :
            if (v_a_id == a) {
                f[a] = m.elem;

                printf("found !\n");
                List path = traceBack(f,d,a);
                free_TwinHeap(openl);
                free_list(closel);
                free(f);
                return path;
            }

            // If already visited, ignore :
            if (contains_list(v_a_id, closel)) continue;

            // Get cost :
            double v_cost = vex[i].cost;
            double known_cost = m_cost + v_cost;
            G_Cell v_a = ithElement_graph(g,m_index, len_graph_list(g));
            double estimated_total_cost = known_cost + v_a.dist;

            //printf("\tVertex : %d:%lf\n",v_a_id, estimated_total_cost);

            // If the cell is already known :
            if (key(openl,openl.data[v_a_id].indexInHeap) != Default) {

                double value = 0;
                // Select the minimum between the known cost and the new one :
                if (estimated_total_cost < key(openl,openl.data[v_a_id].indexInHeap)) {
                    value = estimated_total_cost;
                    // Change its father :
                    f[v_a_id] = m.elem;
                } else value = key(openl,openl.data[v_a_id].indexInHeap);

                // And change the key accordingly :
                int temp = *openl.len;
                *openl.len = len;
                editKey(openl, v_a_id, value);
                *openl.len = temp;

            // If it isn't known :
            } else {
                // Add the cell in the open list :
                int new_len = *openl.len + 1;
                *openl.len = len;
                editKey(openl, v_a_id, estimated_total_cost);
                *openl.len = new_len;

                // And set his father :
                f[v_a_id] = m.elem;
            }
        }
    }

    printf("not found...\n");
    free_TwinHeap(openl);
    free_list(closel);
    free(f);
    return NULL;
}

int main(int argc, char *argv[]) {
	//Get the data from Json
    Data_list data = GetData("test.json", "test2.json", "test3.json");
    //printf("Start : %d, End : %d\n", data.dep, data.arr);

	//Put the data into variable
    const Graph_List g = data.graph;
    Vertex *vex = &data.vex;
    const size_t vex_size = data.vex_len;

    const Element dep = data.dep;
    const Element arr = data.arr;

	//Search the path
    List path = a_star(g,*vex,dep,arr);

	//Put it into a file for the php
	createFile(path,dep,arr,argv[1],argv[2]);
	printf("Un truc au pif");

    // Don't forget to free everything :
    free_Graph(g);
    free_Vertex(vex,vex_size);
    free_list(path);



    return EXIT_SUCCESS;
}
